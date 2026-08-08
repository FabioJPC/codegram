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
        $firstImage = $this->images->first();

        $user = auth()->user();

        return [
            'id' => $this->id,
            'caption' => $this->caption,
            'mediaUrl' => url('storage/' . $firstImage->path),
            'likesCount' => $this->likes_count,
            'isLikedByMe' => $user ? $this->likes->contains('user_id', $user->id) : false,
            'author' => [
                'username' => $this->user->username,
                'avatarUrl' => $this->user->avatar_path
                    ? url('storage/' . $this->user->avatar_path)
                    : null
            ],
        ];
    }
}
