<?php

namespace App\Services\Api;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(
        private FileService $fileService
    ){}

    public function me(int $id): array
    {
        return ['data' => User::findOrFail($id)];
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
}
