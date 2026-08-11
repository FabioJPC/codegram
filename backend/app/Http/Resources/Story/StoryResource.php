<?php

namespace App\Http\Resources\Story;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
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

            'url' => url('storage/' . $this->path),

            'expiresAt' => $this->expires_at,

            'author' => [
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
