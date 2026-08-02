<?php

namespace App\Services\Api;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    public function storeProfilePhoto(UploadedFile $newPhoto): string
    {
        $hashName = Str::uuid() . '.' . $newPhoto->extension();

        $path = $newPhoto->storeAs('profile-photos', $hashName, 'public');

        return $path;
    }

    public function deleteProfilePhoto(string $deletePath): bool
    {
        if ($deletePath && Storage::disk('public')->exists($deletePath)) {
            Storage::disk('public')->delete($deletePath);
            return true;
        }

        return false;
    }

    public function storeImage(UploadedFile $image, string $folder): string
    {
        $hashName = Str::uuid() . '.' . $image->extension();

        $path = $image->storeAs($folder, $hashName);

        return $path;
    }

    public function deleteImage(string $path): void
    {
        Storage::disk('local')->delete($path);
    }

    public function deleteDirectory(string $path): void
    {
        Storage::disk('local')->deleteDirectory($path);
    }
}
