<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'day' => 'required|integer|between:1,31',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer',
            'avatar_url' => 'nullable|file|max:2048',
            'head_avatar_url' => 'nullable|file|max:2048',
            'about' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name required.',
            'surname.required' => 'Last name is required.',
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
