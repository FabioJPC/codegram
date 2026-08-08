<?php

namespace App\Services\Api;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FollowService
{
    public function follow(User $currentUser, User $toFollow): bool
    {
        if ($currentUser->id === $toFollow->id) {
            return false;
        }

        $follow = Follow::firstOrCreate([
            'follower_id'  => $currentUser->id,
            'following_id' => $toFollow->id,
        ]);

        return $follow->wasRecentlyCreated;
    }

    public function unfollow(User $currentUser, User $toUnfollow): bool
    {
        if ($currentUser->id === $toUnfollow->id) {
            return false;
        }

        $deletedRows = $currentUser->following()->detach($toUnfollow->id);

        return $deletedRows > 0;
    }

    public function followers(User $user, int $perPage): LengthAwarePaginator
    {
        return $user->followers()->paginate($perPage);
    }

    public function following(User $user, int $perPage): LengthAwarePaginator
    {
        return $user->following()->paginate($perPage);
    }

    public function suggestions(User $user): Collection
    {
        $followingIds = $user->following()->pluck('users.id');

        return User::query()
            ->where('id', '!=', $user->id)
            ->whereNotIn('id', $followingIds)
            ->limit(5)
            ->get();
    }
}
