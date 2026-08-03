<?php

namespace App\Services\Api;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

class LikeService
{
    public function toggleLike(User $user, Post $post): array
    {
        $like = Like::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($like) {
            $like->delete();
            $isLiked = false;
        } else {
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id
            ]);
            $isLiked = true;
        }

        return [
            'liked'       => $isLiked,
            'likes_count' => $post->likes()->count()
        ];
    }
}

