<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'mobile_number' => [
                'required',
                'unique:users,mobile_number,' . auth()->id(),
                'min:8',
                'max:13',
            ],
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'country_code' => 'required',
            'name' => 'sometimes|required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'dob.required' => 'The date of birth field is required.',
            'dob.before_or_equal' => 'You must be at least 18 years old.'
        ];
    }
}
