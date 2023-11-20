<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function signUp($inputs)
    {
        $user  = new User;
        $user->fill($inputs->except('avatar'));
        $originalName = $inputs->avatar->getClientOriginalName();
        $timestamp = time();
        $avatar = $timestamp . '_' . $originalName;
        $user->avatar = $avatar;
        $user->save();

        $inputs->avatar->move(public_path('/storage/avatar'), $avatar);
        return response()->json([
            'message' => 'User Created Successfully',
            'success' => true
        ],200);
    }

    public function login($inputs)
    {
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $inputs->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'message' => 'You have logged in successfully.',
            'accessToken' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'success' => true,
        ], 200);
    }

    public function logout($request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
