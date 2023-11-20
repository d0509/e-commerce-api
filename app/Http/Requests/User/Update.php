<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Update extends FormRequest
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
    public function rules(): array
    {
        return [
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email|max:255|unique:users,email,'.Auth::id(),
            'address' => 'required',
            'mobile_no'=>['required','numeric','digits:10', Rule::unique('users')->ignore(Auth::user()),],
            'city_id' => 'required|exists:cities,id',
            'avatar' => 'nullable|image:jpeg,png,jpg,svg|max:2048',
        ];
    }
}
