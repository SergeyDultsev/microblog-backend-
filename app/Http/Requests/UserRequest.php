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
        ];
    }

    public function messages(): array
    {
        return [
            'year.integer' => 'The "Year" field must be an integer.',
            'month.between' => 'The "Month" field must be between 1 and 12.',
            'day.between' => 'The "Day" field must be between 1 and 31.',
        ];
    }
}
