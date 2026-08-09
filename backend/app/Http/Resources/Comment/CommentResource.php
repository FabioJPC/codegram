<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'comment' => $this->comment,

            'isMine' => $request->user()->id === $this->user_id,

            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'name' => $this->user->name,

                'avatarUrl' => $this->user->profile_photo
                    ? url('storage/' . $this->user->profile_photo)
                    : null,
            ],
        ];
    }
}
