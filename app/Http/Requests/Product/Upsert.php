<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
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
    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'description' => 'required',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'is_active' => 'required|boolean',
            'category_id' => 'required|array|exists:categories,id',
            'image' => 'required|exists:media,ulid',
            
        ];
    }

}
