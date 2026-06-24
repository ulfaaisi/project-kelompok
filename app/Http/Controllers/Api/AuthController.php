<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle proses registrasi user baru dari frontend React.
     */
    public function register(Request $request): JsonResponse
    {
        // 1. Validasi data yang masuk dari React
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Jika validasi gagal, langsung kembalikan JSON 422 (Mencegah Laravel melakukan redirect ke '/')
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Simpan user baru ke database Laragon
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password aman
        ]);

        // 3. Buat token akses Sanctum secara instan (Aman setelah HasApiTokens dipasang di User.php)
        Auth::login($user);

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'data' => $user
        ], 201);

        // 4. Kembalikan respons sukses dalam bentuk JSON murni (Status 201 Created)
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Registrasi berhasil',
    //         'data' => $user,
    //         'token' => $token
    //     ], 201);
    }

    /**
     * Handle proses login user.
     */
    public function login(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    if (!Auth::attempt(
        $request->only('email', 'password'),
        true
    )) {
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah.'
        ], 401);
    }

    $request->session()->regenerate();

    return response()->json([
        'success' => true,
        'data' => Auth::user()
    ]);
}

    /**
     * Ambil data user yang sedang login (me).
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ], 200);
    }

    /**
     * Handle proses logout user.
     */
    public function logout(Request $request): JsonResponse
    {
        // Hapus token user saat ini
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout'
        ], 200);
    }
}
