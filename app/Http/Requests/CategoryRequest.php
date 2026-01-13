<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $categoryId = $this->route('category') ? $this->route('category')->id : null;
        return [
            'name' => 'required|string|max:255|unique:categories,name' . ($categoryId ? ',' . $categoryId : ''),
            'description' => 'required|string',
        ];
    }
}
