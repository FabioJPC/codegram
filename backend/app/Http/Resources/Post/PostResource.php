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
        $user = auth()->user();

        return [
            'id' => $this->id,

            'caption' => $this->caption,

            'images' => $this->images->map(function ($image) {
                return [
                    'id'        => $image->id,
                    'url'       => url('storage/' . $image->path),
                    'position'  => $image->position,
                ];
            }),

            'isLikedByMe' => $user ? $this->likes->contains('user_id', $user->id) : false,
            'likesCount' => $this->likes_count,

            'commentsCount' => $this->comments_count,

            'author' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'name' => $this->user->name,
                'avatarUrl' => $this->user->profile_photo
                    ? url('storage/' . $this->user->profile_photo)
                    : null
            ],
        ];
    }
}
