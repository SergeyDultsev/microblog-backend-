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
            'year.required' => 'The "Year" field is required.',
            'year.integer' => 'The "Year" field must be an integer.',
            'month.required' => 'The "Month" field is required.',
            'month.integer' => 'The "Month" field must be an integer.',
            'month.between' => 'The "Month" field must be between 1 and 12.',
            'day.required' => 'The "Day" field is required.',
            'day.integer' => 'The "Day" field must be an integer.',
            'day.between' => 'The "Day" field must be between 1 and 31.',
        ];
    }
}
