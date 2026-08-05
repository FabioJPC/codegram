<?php

namespace App\Services\Api;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

use App\Services\Concerns\AuthorizeOwnership;

class LikeService
{
    use AuthorizeOwnership;

    public function store(User $user, Post $post): array
    {
        $like = Like::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        $isLiked = false;

        if (! $like) {
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

    public function destroy(User $user, Post $post): array
    {
        $like = Like::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        $isLiked = true;

        if ($like) {
            $this->authorizeOwnership($user, $like);

            $like->delete();
            $isLiked = false;
        }

        return [
            'liked'       => $isLiked,
            'likes_count' => $post->likes()->count()
        ];
    }
}

