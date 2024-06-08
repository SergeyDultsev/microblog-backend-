<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text_content' => 'nullable|string',
            'media_content' => 'nullable|file|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'text_content.string' => 'Text content must be a string.',
            'media_content.max' => 'Media content must be no larger than 2MB.',
        ];
    }
}
