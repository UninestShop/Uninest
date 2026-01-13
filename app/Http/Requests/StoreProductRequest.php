<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'condition' => 'required|in:New,Like New,Good,Fair,Poor',
            'mrp' => 'required|numeric|min:0',
            'payment_method.*' => 'required',
            'photos' => 'required|array',
            // 'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp,svg',
            'location' => 'required',
        ];
    }
}
