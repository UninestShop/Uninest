<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
            'payment_method' => 'sometimes|string|in:cash,card,transfer',
            'notes' => 'nullable|string|max:255',
        ];
    }
}
