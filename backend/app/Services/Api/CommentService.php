<?php

namespace App\Services\Api;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\Concerns\AuthorizeOwnership;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentService
{
    use AuthorizeOwnership;

    public function index(Post $post, int $perPage): LengthAwarePaginator
    {
        return $post->comments()
            ->with(['user:id,name,username,profile_photo'])
            ->latest()
            ->paginate($perPage);
    }

    public function store(User $user, Post $post, array $data): Comment
    {
        $comment = $post->comments()->create([
            'user_id' => $user->id,
            'comment' => $data['comment'],
        ]);

        return $comment->load('user:id,name,username,profile_photo');
    }

    public function update(User $user, array $data, Comment $comment): Comment
    {
        $this->authorizeOwnership($user, $comment);

        $comment->update([
            'comment' => $data['comment']
        ]);

        return $comment->load('user:id,name,username,profile_photo');
    }

    public function destroy(User $user, Comment $comment): void
    {
        $this->authorizeOwnership($user, $comment);

        $comment->delete();
    }
}
