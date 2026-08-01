<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\FollowService;

class FollowController extends Controller
{
    public function __construct(
        private FollowService $followService
    ){}

    public function follow(Request $request, User $toFollow)
    {
        $currentUser = $request->user();

        $result = $this->followService->follow($currentUser, $toFollow);

        $status = $result ? 201 : 422;
        $message = $result ? 'Você seguiu este usuário' : 'Não foi possível seguir este usuário';

        return response()->json(['message' => $message], $status);
    }

    public function unfollow(Request $request, User $toUnfollow)
    {
        $currentUser = $request->user();

        $result = $this->followService->unfollow($currentUser, $toUnfollow);

        if ($result) {
            return response()->noContent();
        }

        return response()->json(['message' => 'Não foi possível realizar essa operação'], 422);
    }

    public function followers(User $user)
    {
        $followers = $this->followService->followers($user, 50);
        return response()->json($followers, 200);
    }

    public function following(User $user)
    {
        $following = $this->followService->following($user, 50);
        return response()->json($following, 200);
    }

}
