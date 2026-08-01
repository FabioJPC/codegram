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
}
