<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    $user = Auth::user();
    $token = $user->createToken('api-token')->plainTextToken;
    return response()->json(['user' => $user, 'token' => $token]);
}

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function logout(Request $request)
{
    $user = $request->user();

    if ($user) {
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    return response()->json(['message' => 'Not authenticated'], 401);
}



}
