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

        $path = $image->storeAs($folder, $hashName, 'public');

        return $path;
    }

    public function deleteImage(string $path): void
    {
        Storage::disk('public')->delete($path);
    }

    public function deleteDirectory(string $path): void
    {
        Storage::disk('public')->deleteDirectory($path);
    }

    public function getPostFiles(array $images)
    {
        $imageFiles = [];

        foreach ($images as $image) {
            $imageFiles[] = Storage::disk('public')->get($image);
        }

        return $imageFiles;
    }

    public function getPostImage(string $path)
    {
        if ( ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return [
            'content' => Storage::disk('public')->get($path),
            'mimeType' => Storage::disk('public')->mimeType($path),
            'fileName' => basename($path)
        ];
    }
}
