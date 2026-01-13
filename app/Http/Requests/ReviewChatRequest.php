<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewChatRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'action' => 'required|in:flag,clear,block_user',
            'reason' => 'required_if:action,flag|string|max:255',
        ];
    }
}
