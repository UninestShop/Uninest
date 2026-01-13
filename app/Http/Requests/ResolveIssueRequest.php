<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResolveIssueRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'resolution' => 'required|string',
            'status' => 'required|in:resolved,rejected',
        ];
    }
}
