<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\Api\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function __construct(
        private CommentService $service
    ){}

    public function index(Request $request, Post $post)
    {
        $perPage = (int) $request->query('per_page') ?? 50;
        return CommentResource::collection($this->service->index($post, $perPage));
    }

    public function store(StoreCommentRequest $request, Post $post)
    {
        $comment = $this->service->store(
            $request->user(),
            $post,
            $request->validated()
        );

        return new CommentResource($comment);
    }

    public function update(StoreCommentRequest $request, Comment $comment)
    {
        $comment = $this->service->update($request->user() ,$request->validated(), $comment);

        return new CommentResource($comment);
    }

    public function destroy(Request $request, Comment $comment): Response
    {
        $this->service->destroy($request->user(), $comment);

        return response()->noContent();
    }

}
