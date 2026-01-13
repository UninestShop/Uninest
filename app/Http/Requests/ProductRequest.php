<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|string|in:New,Like New,Good,Fair,Poor',
            'meetup_location' => 'required|string|max:255',
            'payment_methods' => 'required|array|min:1',
        ];
        
        // For store method, image is required
        if ($this->isMethod('post')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,jpg,png,gif,bmp,svg,webp|max:2048';
        }
        
        // For update method, image is optional
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['image'] = 'nullable|image|max:2048';
        }
        
        return $rules;
    }
}
