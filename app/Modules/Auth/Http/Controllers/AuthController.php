<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function apiLogin(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    $user = Auth::user();

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
}
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                'user' => Auth::user(),
                'message' => 'Login successful',
            ]);
        }

        return response()->json([
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id' => $user?->id,
            'name' => $user?->name,
            'image' => $user?->image,
            'email' => $user?->email,
            'role' => $user?->role,
        ]);
    }
}
