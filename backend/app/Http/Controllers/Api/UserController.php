<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Profile\UserProfileResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\Api\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ){}

    public function show(Request $request)
    {
        return new UserResource(
            $this->userService->me($request->user()->id)
        );
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();

        $photo = $request->file('profile_photo');

        $user = $this->userService->update($request->user(), $data, $photo);

        return new UserResource($user);
    }

    public function showProfile(User $user)
    {
        return new UserProfileResource($this->userService->showProfile($user));
    }

    public function posts(User $user)
    {
        $posts = $this->userService->posts($user);

        return PostResource::collection($posts);
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->query('query', ''));

        if ($query === '') {
            return UserProfileResource::collection([]);
        }

        $users = $this->userService->search($query, $request->user());

        return UserProfileResource::collection($users);
    }
}
