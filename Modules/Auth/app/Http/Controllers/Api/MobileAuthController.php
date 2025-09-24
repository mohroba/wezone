<?php

namespace Modules\Auth\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Modules\Auth\Http\Requests\SendOtpRequest;
use Modules\Auth\Http\Requests\VerifyOtpRequest;
use Modules\Auth\Http\Resources\ProfileResource;
use Modules\Auth\Jobs\SendSmsMessage;
use Modules\Auth\Models\Otp;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class MobileAuthController extends Controller
{
    private const OTP_LENGTH = 6;
    private const OTP_EXPIRATION_MINUTES = 2;
    private const RATE_LIMIT_SECONDS = 120;

    public function send(SendOtpRequest $request): JsonResponse
    {
        $mobile = $request->validated()['mobile'];
        $rateLimiterKey = $this->rateLimiterKey($mobile);

        if (RateLimiter::tooManyAttempts($rateLimiterKey, 1)) {
            return ApiResponse::error(
                'Please wait before requesting another OTP.',
                Response::HTTP_TOO_MANY_REQUESTS,
                ['retry_after' => RateLimiter::availableIn($rateLimiterKey)]
            );
        }

        $code = str_pad((string) random_int(0, (10 ** self::OTP_LENGTH) - 1), self::OTP_LENGTH, '0', STR_PAD_LEFT);

        Otp::create([
            'mobile' => $mobile,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::OTP_EXPIRATION_MINUTES),
            'meta' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        RateLimiter::hit($rateLimiterKey, self::RATE_LIMIT_SECONDS);

        SendSmsMessage::dispatch($mobile, 'verify', ['code' => $code])->onQueue('sms');

        return ApiResponse::success(
            'OTP has been sent successfully.',
            [
                'expires_in' => self::OTP_EXPIRATION_MINUTES * 60,
            ],
            Response::HTTP_CREATED
        );
    }

    public function verify(VerifyOtpRequest $request): JsonResponse
    {
        $data = $request->validated();
        $mobile = $data['mobile'];

        $otp = Otp::where('mobile', $mobile)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (! $otp || $otp->hasExpired() || ! Hash::check($data['otp'], $otp->code)) {
            if ($otp) {
                $otp->incrementAttempts();
            }

            return ApiResponse::error('Invalid or expired OTP.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $existingUser = User::withTrashed()->where('mobile', $mobile)->first();

        $usernameProvided = array_key_exists('username', $data);
        $username = $data['username'] ?? null;
        if ($username) {
            $usernameQuery = User::where('username', $username);
            if ($existingUser) {
                $usernameQuery->where('id', '!=', $existingUser->id);
            } else {
                $usernameQuery->where('mobile', '!=', $mobile);
            }

            if ($usernameQuery->exists()) {
                return ApiResponse::error(
                    'The chosen username is already in use.',
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    ['username' => ['The username has already been taken.']]
                );
            }
        }

        $emailProvided = array_key_exists('email', $data);
        $email = $data['email'] ?? null;
        if ($emailProvided && $email) {
            $emailQuery = User::where('email', $email);
            if ($existingUser) {
                $emailQuery->where('id', '!=', $existingUser->id);
            }

            if ($emailQuery->exists()) {
                return ApiResponse::error(
                    'The provided email address is already in use.',
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    ['email' => ['The email has already been taken.']]
                );
            }
        }

        $user = DB::transaction(function () use ($data, $mobile, $otp, $username, $usernameProvided, $email, $emailProvided, $existingUser) {
            $otp->markAsUsed();

            if ($existingUser && $existingUser->trashed()) {
                $existingUser->restore();
            }

            $user = $existingUser ?? new User();

            if (! $user->exists) {
                $user->mobile = $mobile;
                $user->username = $username ?: $this->generateUsernameFromMobile($mobile);
            } else {
                if ($usernameProvided && $username !== null) {
                    $user->username = $username;
                } elseif (! $user->username) {
                    $user->username = $this->generateUsernameFromMobile($mobile);
                }
            }

            if ($emailProvided) {
                $user->email = $email;
            }

            $user->save();

            $profile = $user->profile()->firstOrCreate([]);

            $profileData = collect($data)
                ->only([
                    'first_name',
                    'last_name',
                    'birth_date',
                    'national_id',
                    'residence_city_id',
                    'residence_province_id',
                ])
                ->filter(static fn ($value) => $value !== null)
                ->toArray();

            if ($profileData !== []) {
                $profile->fill($profileData)->save();
            }

            return $user->fresh('profile');
        });

        RateLimiter::clear($this->rateLimiterKey($mobile));

        $tokenResult = $user->createToken('mobile-auth');
        $tokenModel = $tokenResult->token;

        if ($tokenModel === null) {
            report(new RuntimeException('Unable to retrieve the issued access token instance.'));

            return ApiResponse::error(
                'Unable to generate authentication tokens. Please try again later.',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        if ($tokenModel->refreshToken) {
            $tokenModel->refreshToken()->delete();
        }

        $refreshToken = Str::random(80);

        $tokenModel->refreshToken()->create([
            'id' => $refreshToken,
            'revoked' => false,
            'expires_at' => now()->add(Passport::refreshTokensExpireIn()),
        ]);

        $profile = $user->profile->loadMissing('user.roles', 'user.permissions');

        $expiresIn = $tokenResult->expiresIn;

        return ApiResponse::success(
            'Authenticated successfully.',
            [
                'access_token' => $tokenResult->accessToken,
                'refresh_token' => $refreshToken,
                'token_type' => $tokenResult->tokenType ?? 'Bearer',
                'expires_in' => $expiresIn !== null ? (int) $expiresIn : null,
                'expires_at' => optional($tokenModel->expires_at)->toDateTimeString(),
                'profile' => new ProfileResource($profile),
            ]
        );
    }

    private function rateLimiterKey(string $mobile): string
    {
        return 'otp:' . $mobile;
    }

    private function generateUsernameFromMobile(string $mobile): string
    {
        $suffix = substr($mobile, -4);

        do {
            $candidate = Str::lower('user_' . $suffix . random_int(100, 999));
        } while (User::where('username', $candidate)->exists());

        return $candidate;
    }
}
