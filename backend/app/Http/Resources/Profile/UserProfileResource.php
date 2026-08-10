<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'bio' => $this->bio,

            'avatarUrl' => $this->profile_photo
                ? url('storage/' . $this->profile_photo)
                : null,

            'postsCount' => $this->posts_count,
            'followersCount' => $this->followers_count,
            'followingCount' => $this->following_count,

            'isFollowing' => $this->isFollowing ?? false,
        ];
    }
}
