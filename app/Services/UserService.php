<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{

    public function resource()
    {
        $user = User::where('id', Auth::id())->get();
        $user->load('city');
        return response()->json(['user' => $user, 'success' => true], 200);
    }

    public function update($inputs, $ulid)
    {
        $user = User::where('id', Auth::id())->first();
        if ($inputs->has('avatar')) {
            unlink((public_path('storage/avatar/')) . $user->avatar);

            $originalName = $inputs->avatar->getClientOriginalName();
            $timestamp = time();
            $avatar = $timestamp . '_' . $originalName;

            $user->fill($inputs->except('avatar'));

            $inputs->avatar->move(public_path('/storage/avatar'), $avatar);
        } else {
            $user->fill($inputs);
        }

        return response()->json([
            'message' => 'User Updated Successfully',
            'success' => true, 'user' => $user
        ], 200);
    }
}
