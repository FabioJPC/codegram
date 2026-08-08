<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\User\UserResource;
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

        return response()->json($this->userService->update($request->user(), $data, $photo));
    }
}
