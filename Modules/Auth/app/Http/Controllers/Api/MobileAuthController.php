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

    /**
     * @group Auth
     *
     * Send mobile OTP
     *
     * Start the passwordless login flow by requesting a six-digit code.
     *
     * @bodyParam mobile string required The mobile number to send the OTP to. Must contain 10 to 15 digits. Example: "989123456789"
     * @response status=201 scenario="OTP created" {
     *   "success": true,
     *   "message": "OTP has been sent successfully.",
     *   "data": {
     *     "expires_in": 120
     *   },
     *   "meta": {}
     * }
     * @response status=429 scenario="Too many attempts" {
     *   "success": false,
     *   "message": "Please wait before requesting another OTP.",
     *   "errors": {
     *     "retry_after": 75
     *   },
     *   "data": null
     * }
     */
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

    /**
     * @group Auth
     *
     * Verify OTP and issue tokens
     *
     * Complete the login flow by exchanging a valid OTP for access credentials.
     *
     * @bodyParam mobile string required The mobile number the OTP was sent to. Must contain 10 to 15 digits. Example: "989123456789"
     * @bodyParam otp string required The six-digit one-time password received by the user. Example: "123456"
     * @bodyParam username string optional A unique username to assign to the user. Example: "sara94"
     * @bodyParam email string optional An email address to associate with the user. Example: "sara@example.com"
     * @bodyParam first_name string optional User's given name. Example: "Sara"
     * @bodyParam last_name string optional User's family name. Example: "Rahimi"
     * @bodyParam birth_date date optional Date of birth in Y-m-d format. Example: "1994-03-18"
     * @bodyParam national_id string optional National identification number. Example: "1234567890"
     * @bodyParam residence_city_id integer optional Identifier of the city where the user resides. Example: 10
     * @bodyParam residence_province_id integer optional Identifier of the province where the user resides. Example: 2
     * @response {
     *   "success": true,
     *   "message": "Authenticated successfully.",
     *   "data": {
     *     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
     *     "refresh_token": "f4b5c29f92f24f3b9e0a2d874e6c8f74b1e9f9e2a6f84715b22d8fca8f4b90de",
     *     "token_type": "Bearer",
     *     "expires_in": 31536000,
     *     "expires_at": "2025-09-25 07:10:00",
     *     "profile": {
     *       "id": 12,
     *       "first_name": "Sara",
     *       "last_name": "Rahimi",
     *       "full_name": "Sara Rahimi",
     *       "birth_date": "1994-03-18",
     *       "national_id": "1234567890",
     *       "residence_city_id": 10,
     *       "residence_province_id": 2,
     *       "user": {
     *         "id": 45,
     *         "mobile": "989123456789",
     *         "username": "sara94",
     *         "email": "sara@example.com",
     *         "roles": [
     *           "customer"
     *         ],
     *         "permissions": []
     *       },
     *       "media": {
     *         "national_id_document": "https://cdn.example.com/media/national-id.pdf",
     *         "profile_images": [
     *           {
     *             "id": "f17c6ae4-5c1a-4c44-a058-9324c4b6f8b9",
     *             "name": "avatar",
     *             "url": "https://cdn.example.com/media/avatar.jpg"
     *           }
     *         ]
     *       },
     *       "created_at": "2025-09-24T12:00:00.000000Z",
     *       "updated_at": "2025-09-25T07:00:00.000000Z"
     *     }
     *   },
     *   "meta": {}
     * }
     * @response status=422 scenario="Invalid OTP" {
     *   "success": false,
     *   "message": "Invalid or expired OTP.",
     *   "errors": {},
     *   "data": null
     * }
     */
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

