<?php

namespace Modules\Auth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Models\Profile;

/**
 * @mixin Profile
 */
class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        $user = $this->whenLoaded('user');

        return [
            'id' => $this->id,
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'full_name' => $this->full_name ?? '',
            'birth_date' => $this->birth_date?->toDateString() ?? '',
            'national_id' => $this->national_id ?? '',
            'residence_city_id' => $this->residence_city_id,
            'residence_province_id' => $this->residence_province_id,
            'user' => $user ? [
                'id' => $user->id,
                'mobile' => $user->mobile,
                'username' => $user->username ?? '',
                'email' => $user->email ?? '',
                'roles' => $user->getRoleNames()->values(),
                'permissions' => $user->getPermissionNames()->values(),
            ] : null,
            'media' => [
                'national_id_document' => optional($this->getFirstMedia(Profile::COLLECTION_NATIONAL_ID))?->getUrl() ?? '',
                'profile_images' => $this->getMedia(Profile::COLLECTION_PROFILE_IMAGES)
                    ->map(fn ($media) => [
                        'id' => $media->uuid ?? $media->id,
                        'name' => $media->name,
                        'url' => $media->getUrl(),
                    ])->values(),
            ],
            'stats' => [
                'ads_count' => (int) ($user?->ads_count ?? 0),
                'ads_total_views' => (int) ($user?->ads_view_sum ?? 0),
                'last_seen_at' => $user?->last_seen_at,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
