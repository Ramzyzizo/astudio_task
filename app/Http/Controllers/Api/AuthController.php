<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\PersonalAccessTokenResult;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $tokenResult = $user->createToken('API Token');
        $token = $tokenResult->accessToken;
        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user->select('id', 'f_name', 'l_name', 'email')->first(),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
        
    }
    // login user
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'email or password is incorrect.'], 401);
        }
        $tokenResult = $user->createToken('API Token');
        $token = $tokenResult->accessToken;

        return response()->json([
            'message' => 'User login successfully!',
            'user' => $user->select('id', 'f_name', 'l_name', 'email')->first(),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    // Logout user
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
