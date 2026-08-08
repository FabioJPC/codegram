<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct(
        private FileService $fileService
    ){}

    public function getFiles(Request $request)
    {
        return $this->fileService->getPostFiles($request->images);
    }

    public function getImage(string $path)
    {
        $imageData = $this->fileService->getPostImage($path);

        if (! $imageData) {
            abort(404, 'A imagem não foi encontrada.');
        }

        return response($imageData['content'], 200)
            ->header('Content-Type', $imageData['mimeType'])
            ->header('Content-Disposition', 'inline:filename="' . $imageData['fileName'] . '"')
            ->header('Cache-Control', 'public, max-age=31536000');
    }

}
