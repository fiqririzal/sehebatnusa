<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Laravel\Sanctum\Tokens\Token;
// use Laravel\Sanctum\Tokens\RefreshToken;

class UserController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        return apiResponse(400, 'error', 'Kamu siapa ko mau masuk web ini?');
    }

    $user = Auth::user();
    $token = $user->createToken('API Token')->plainTextToken;

    $data = [
        'token' => $token,
        'user' => $user,
    ];

    return apiResponse(200, 'success', 'Selamat anda berhasil Login', $data);
}
public function logout()
{
    $user = auth()->user();

    if ($user) {
        $tokens = $user->tokens;

        foreach ($tokens as $token) {
            $token->delete();
        }
    }

    return apiResponse(200, 'success', 'Berhasil logout');
}
}
