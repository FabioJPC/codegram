<?php

namespace App\Services\Api;

use App\Models\Post;
use App\Models\User;
use App\Models\PostImage;
use Illuminate\Support\Facades\DB;
use App\Services\Concerns\AuthorizeOwnership;

class PostService
{
    use AuthorizeOwnership;

    public function __construct(
        private FileService $fileService
    ){}

    public function show(Post $post): Post
    {
        return $post->load([
            'user',
            'images'
        ]);
    }

    public function store(User $user, array $data, array $images): Post
    {
        $storedPaths = [];

        try {
            return DB::transaction(function () use ($user, $data, $images, &$storedPaths) {
                $post = Post::create([
                    'user_id' => $user->id,
                    'caption' => $data['caption'] ?? null,
                ]);

                foreach ($images as $index => $image) {
                    $folder = "posts/{$user->id}/{$post->id}";

                    $path = $this->fileService->storeImage($image, $folder);

                    $storedPaths[] = $path;

                    PostImage::create([
                        'post_id'   => $post->id,
                        'path'      => $path,
                        'position'  => ($index + 1)
                    ]);
                }

                return $post->load(['user', 'images']);
            });
        } catch (\Throwable $e){
            foreach ($storedPaths as $path) {
                $this->fileService->deleteImage($path);
            }

            throw $e;
        }
    }

    public function update(User $user, array $data, Post $post): Post
    {
        $this->authorizeOwnership($user, $post);

        $post->update($data);
        return $post->load(['user', 'images']);
    }

    public function destroy(User $currentUser, Post $post): void
    {
        $this->authorizeOwnership($currentUser, $post);

        $post->delete();
    }

    public function feed(User $user): array
    {
        $followingIds = $user->following()
            ->pluck('users.id');

        $followingIds->push($user->id);

        $posts = Post::query()
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->with(['user', 'images'])
            ->paginate(10)


    }
}
