<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => Auth::user()
        ]);
    }

    public function register(Request $request)
    {
        return app(\App\Http\Controllers\AuthController::class)
            ->register($request);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json([
            'success' => true
        ]);
    }
}
