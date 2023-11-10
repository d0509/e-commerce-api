<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Upsert extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        
        $rules = [];
         $rules = [
            'city_id' => 'required|exists:cities,id',
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email|max:255|unique:users,email,',
            'address' => 'required',
            'mobile_no' => 'required|numeric|regex:/^(?!0+$)0?[1-9][0-9]*$/|digits:10|unique:users,mobile_no,'.Auth::id(),            
        ];

        if($this->_method == 'PUT'){
            $rules['email'] .=  auth()->user()->id;
            $rules['avatar'] = 'nullable|image:jpeg,png,jpg,svg|max:2048';
            // dd($rules);
        } else {
            $rules['password'] = 'required|min:6|max:10';
            $rules['confirm_password'] = 'required|same:password';
            $rules['avatar'] = 'required|image:jpeg,png,jpg,svg|max:2048';
        }

        return $rules;

    }
}
