<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'string|required|max:100',
            'instance' => 'string|required|max:100',
            'phone_number' => 'string|required|max:100',
            'avatar' => 'required|image|max:4096',
            'email' => 'string|required|max:100|unique:App\Models\User,email',
            'password' => 'confirmed|required|string|max:100|min:5',
            'password_confirmation' => 'required|string',
        ];
    }
}
