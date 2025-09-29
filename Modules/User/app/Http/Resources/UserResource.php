<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
                return [
                    'first_name' => $this->profile->first_name,
                    'last_name' => $this->profile->last_name,
                    'full_name' => $this->profile->full_name,
                    'birth_date' => optional($this->profile->birth_date)->toDateString(),
                    'residence_city_id' => $this->profile->residence_city_id,
                    'residence_province_id' => $this->profile->residence_province_id,
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
