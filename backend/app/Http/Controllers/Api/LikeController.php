<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\Api\LikeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct(
        private LikeService $service
    ){}

    public function store(Request $request, Post $post): JsonResponse
    {
        $response = $this->service->store($request->user(), $post);
        return response()->json(['data' => $response]);
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        $response = $this->service->destroy($request->user(), $post);
        return response()->json(['data' => $response]);
    }
}
