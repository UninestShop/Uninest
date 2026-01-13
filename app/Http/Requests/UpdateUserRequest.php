<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userslug = $this->route('user'); // Assuming the route parameter is named 'user'
        $userid=User::where('slug', $userslug)->firstOrFail(); // Ensure the user exists
        return [
            'name' => 'nullable|string|max:255',
            'mobile_number' => 'required|unique:users,mobile_number,' . $userid->id,
            'dob' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        ];
    }
}
