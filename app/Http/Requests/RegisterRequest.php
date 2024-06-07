<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'day' => 'required|integer|between:1,31',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name required.',
            'surname.required' => 'Last name is required.',
            'email.required' => 'Email required.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'This email address is already in use.',
            'password.required' => 'The Password field is required.',
            'password.min' => 'The password must contain at least 6 characters.',
        ];
    }
}
