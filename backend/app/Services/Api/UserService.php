<?php

namespace App\Services\Api;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(
        private FileService $fileService
    ){}

    public function me(int $id)
    {
        return User::findOrFail($id);
    }

    public function update(User $user, array $data, ?UploadedFile $photo)
    {
        $newPhotoPath = null;
        $oldPhotoPath = null;

        try {
            if ($photo) {
                $oldPhotoPath = $user->profile_photo;

                $newPhotoPath = $this->fileService->storeProfilePhoto($photo);

                $data['profile_photo'] = $newPhotoPath;
            }

            DB::transaction(function() use ($user, $data) {
                $user->update($data);
            });

            if ($oldPhotoPath) {
                $this->fileService->deleteProfilePhoto($oldPhotoPath);
            }

        } catch (\Throwable $e) {

            if($newPhotoPath) {
                $this->fileService->deleteProfilePhoto($newPhotoPath);
            }

            throw $e;
        }

        return ['data' => $user->fresh()];
    }

    public function showProfile(User $user): User
    {
        $authUser = auth()->user();

        $user->loadCount([
            'posts',
            'followers',
            'following'
        ]);

        $user->isFollowing = $authUser
            ? $authUser->following()
                ->where('users.id', $user->id)
                ->exists()
            : false;

        return $user;
    }

    public function posts(User $user): LengthAwarePaginator
    {
       return $user->posts()
            ->latest()
            ->with('user', 'images', 'likes')
            ->withCount('likes', 'comments')
            ->paginate('12');
    }
}
