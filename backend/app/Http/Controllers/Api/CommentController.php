<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
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

    public function index(Request $request, Post $post): JsonResponse
    {
        $perPage = (int) $request->query('per_page') ?? 50;
        return response()->json(['data' => $this->service->index($post, $perPage)]);
    }

    public function store(StoreCommentRequest $request, Post $post): JsonResponse
    {
        $comment = $this->service->store(
            $request->user(),
            $post,
            $request->validated()
        );

        return response()->json([
            'message'   => 'Comentário enviado com sucesso.',
            'data'      => $comment
        ]);
    }

    public function update(StoreCommentRequest $request, Comment $comment): JsonResponse
    {
        $comment = $this->service->update($request->user() ,$request->validated(), $comment);

        return response()->json(['message' => 'Comentário atualizado', 'data' => $comment]);
    }

    public function destroy(Request $request, Comment $comment): Response
    {
        $this->service->destroy($request->user(), $comment);

        return response()->noContent();
    }

}
