<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Api\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ){}

    public function login(LoginRequest $request)
    {
        return response()->json($this->authService->login($request->validated()));
    }

    public function register(RegisterRequest $request)
    {
        return response()->json($this->authService->register($request->validated()));
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->noContent();
    }

    public function logoutAll(Request $request)
    {
        $this->authService->logoutAll($request->user());

        return response()->noContent();
    }
}
