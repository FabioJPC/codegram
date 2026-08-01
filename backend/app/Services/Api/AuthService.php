<?php

namespace App\Services\Api;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw new AuthenticationException('Credenciais Inválidas.');
        }

        return $this->issueToken($user);
    }

    public function register(array $data): array
    {
        $user = User::create($data);

        return $this->issueToken($user);
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function logoutAll(User $user): void
    {
        $user->tokens()->delete();
    }

    private function issueToken(User $user): array
    {
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'data' => [
                'user'          => $user,
                'token'         => $token,
                'token_type'    => 'Bearer',
            ],
        ];
    }
}
