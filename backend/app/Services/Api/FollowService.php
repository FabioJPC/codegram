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
        $followers = $user->followers()->paginate($perPage);

        return $this->attachFollowingState($followers, auth()->user());
    }

    public function following(User $user, int $perPage): LengthAwarePaginator
    {
        $following = $user
            ->following()
            ->paginate($perPage);

        return $this->attachFollowingState($following, auth()->user());
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

    private function attachFollowingState(LengthAwarePaginator $users, ?User $authUser): LengthAwarePaginator
    {
        if (!$authUser) {
            return $users;
        }

        $followingIds = $authUser
            ->following()
            ->pluck('users.id')
            ->toArray();

        $users->getCollection()->transform(
            function (User $user) use ($followingIds) {
                $user->isFollowing = in_array(
                    $user->id,
                    $followingIds
                );

                return $user;
            }
        );

        return $users;
    }
}
