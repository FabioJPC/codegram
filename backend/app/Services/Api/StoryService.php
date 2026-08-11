<?php

namespace App\Services\Api;

use App\Models\Story;
use App\Models\User;
use App\Services\Concerns\AuthorizeOwnership;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class StoryService
{
    use AuthorizeOwnership;

    private const LIFESPAN_IN_HOURS = 24;

    public function __construct(
        private FileService $fileService
    ){}

    public function store(User $user, UploadedFile $image): Story
    {
        $folder = "stories/{$user->id}";

        $path = $this->fileService->storeImage($image, $folder);

        try {
            $story = Story::create([
                'user_id'    => $user->id,
                'path'       => $path,
                'expires_at' => now()->addHours(self::LIFESPAN_IN_HOURS),
            ]);

            return $story->load('user');

        } catch (\Throwable $e) {
            $this->fileService->deleteImage($path);

            throw $e;
        }
    }

    public function destroy(User $currentUser, Story $story): void
    {
        $this->authorizeOwnership($currentUser, $story);

        $story->delete();
    }

    public function feed(User $user): Collection
    {
        $followingIds = $user->following()->pluck('users.id');

        $followingIds->push($user->id);

        return Story::query()
            ->whereIn('user_id', $followingIds)
            ->notExpired()
            ->with('user')
            ->oldest()
            ->get();
    }
}
