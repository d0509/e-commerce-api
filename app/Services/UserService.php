<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService{

    public function resource(){
        $user = User::where('id',Auth::id())->get();
        return response()->json(['user' => $user, 'success'=>true],200);
    }

    public function show($id){
        $userData = User::where('id',Auth::id())->first();
        if($userData){
            $user = User::where('id', Auth::id())->select('first_name','last_name','email','mobile_no','address','avatar')->with('city')->get();
            return response()->json([
                'user' => $user,
                'success' =>true,
            ]);
        } else {
            return response()->json(['message'=>'Sorry! requested user not found.' ,'success' => false]) ;
        }
        
    }

    public function update($inputs,$ulid){
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

        return response()->json(['message' => 'User Updated Successfully','success' => true,'user' => $user],200);

    }

}