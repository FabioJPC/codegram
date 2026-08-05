<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'caption' => $this->caption,

            'mediaUrl' => $this->images->first()?->image_path,

            'likesCount' => $this->likes_count,

            'isLikedByMe' => false,

            'author' => [
                'username' => $this->user->username,
                'avatarUrl' => $this->user->profile_photo
            ]
        ];
    }
}
