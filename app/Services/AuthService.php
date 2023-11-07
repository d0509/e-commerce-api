<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthService
{
    public function signUp($inputs)
    {
        try {

            $originalName = $inputs->avatar->getClientOriginalName();

            $timestamp = time();

            $avatar = $timestamp . '_' . $originalName;

            User::create([
                'first_name' => $inputs->first_name,
                'last_name' => $inputs->last_name,
                'email' => $inputs->email,
                'password' => Hash::make($inputs->password),
                'city_id' => $inputs->city_id,
                'address' => $inputs->address,
                'avatar' => $avatar,
                'mobile_no'  => $inputs->mobile_no,
            ]);

            $inputs->avatar->move(public_path('/storage/avatar'), $avatar);
            return response()->json(['message' => 'User Created Successfully', 'success' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => $e], 500);
        }
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
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout($request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
