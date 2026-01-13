<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductManagementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'mrp' => 'required|numeric|min:0',
            'user_id' => 'nullable|exists:users,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'condition' => 'required|string',
        ];
    }
}
