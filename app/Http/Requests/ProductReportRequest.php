<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductReportRequest extends FormRequest
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
            'report_reason' => 'required|string',
            'report_details' => 'nullable|string|max:1000',
        ];
    }
}
