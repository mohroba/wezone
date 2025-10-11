<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Models\Profile as ProfileModel;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'username' => $this->username,
            'profile' => $this->whenLoaded('profile', function () {
                $profile = $this->profile;
                $profileImages = $profile->getMedia(ProfileModel::COLLECTION_PROFILE_IMAGES);
                $primaryImage = $profileImages->first();

                return [
                    'first_name' => $profile->first_name,
                    'last_name' => $profile->last_name,
                    'full_name' => $profile->full_name,
                    'birth_date' => optional($profile->birth_date)->toDateString(),
                    'residence_city_id' => $profile->residence_city_id,
                    'residence_province_id' => $profile->residence_province_id,
                    'media' => [
                        'avatar_url' => $primaryImage?->getUrl(),
                        'profile_images' => $profileImages
                            ->map(static fn ($media) => [
                                'id' => $media->uuid ?? $media->id,
                                'name' => $media->name,
                                'url' => $media->getUrl(),
                            ])
                            ->values(),
                    ],
                ];
            }),
            'followers_count' => $this->when(isset($this->followers_count), $this->followers_count),
            'following_count' => $this->when(isset($this->following_count), $this->following_count),
            'followed_at' => $this->whenPivotLoaded('user_follows', function () {
                return optional($this->pivot->created_at)->toAtomString();
            }),
            'created_at' => optional($this->created_at)->toAtomString(),
            'updated_at' => optional($this->updated_at)->toAtomString(),
        ];
    }
}
