<?php

namespace App\Services;

use App\Contracts\AuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function login(array $credentials): ?array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return null;
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function me(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}