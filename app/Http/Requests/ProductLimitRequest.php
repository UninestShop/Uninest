<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductLimitRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_limit' => 'required|integer|min:0',
        ];
    }
}
