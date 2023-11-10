<?php

namespace App\Services;

use App\Models\User;
use Exception;
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
        $user = User::where('id',Auth::id())->first();
        if ($inputs->has('avatar')) {
            unlink((public_path('storage/avatar/')) . $user->avatar);

            $originalName = $inputs->avatar->getClientOriginalName();
            $timestamp = time();
            $avatar = $timestamp . '_' . $originalName;

            $user->update([
                'first_name' => $inputs->first_name,
                'last_name' => $inputs->last_name,
                'email' => $inputs->email,
                'city_id' => $inputs->city_id,
                'address' => $inputs->address,
                'avatar' => $avatar,
                'mobile_no'  => $inputs->mobile_no,
            ]);

            $inputs->avatar->move(public_path('/storage/avatar'), $avatar);
        } else {
            $user->update([
                'first_name' => $inputs->first_name,
                'last_name' => $inputs->last_name,
                'email' => $inputs->email,
                'city_id' => $inputs->city_id,
                'address' => $inputs->address,
                'mobile_no'  => $inputs->mobile_no,
            ]);
        }

        return response()->json(['message' => 'User Updated Successfully', 'success' => true, 'user' => $user], 200);
    }
}
