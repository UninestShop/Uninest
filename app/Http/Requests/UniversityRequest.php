<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UniversityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            // 'status' => 'required|in:active,inactive',
            'domains' => 'required|string|max:255',
        ];
    }
}
