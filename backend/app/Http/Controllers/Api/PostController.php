<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Services\Api\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(
        private PostService $postService
    ){}

    public function show(Post $post)
    {
        return response()->json(['data' => $this->postService->show($post)]);
    }

    public function store(CreatePostRequest $request)
    {
        $validated = $request->validated();

        $post = $this->postService->store(
            $request->user(),
            $validated,
            $request->file('images')
        );

        return response()->json(['data' => $post], 201);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post = $this->postService->update(
            $request->user(),
            $request->validated(),
            $post
        );
        return response()->json(['data' => $post]);
    }

    public function destroy(Request $request, Post $post)
    {
        $this->postService->destroy($request->user(), $post);

        return response()->noContent();
    }

    public function feed(Request $request)
    {
        $posts = $this->postService->feed($request->user());

        return response()->json(PostResource::collection($posts));
    }
}
