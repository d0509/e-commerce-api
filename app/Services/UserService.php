<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService{
    public function index($inputs){
        $user = User::where('id', Auth::id())->select('first_name','last_name','email','mobile_no','address','avatar')->with('city')->get();
        return response()->json([
            'user' => $user,
            'success' =>true,
        ]);
    }

    public function update($inputs,$user){
        $user = Auth::user();
        if($inputs->has('avatar')){
            unlink((public_path('storage/avatar/')).$user->avatar);

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

        return response()->json(['message' => 'User Updated Successfully','success' => true]);

    }

}