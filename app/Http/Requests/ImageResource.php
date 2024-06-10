<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageResource extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'avatar_url' => 'nullable|file|max:2048',
            'head_avatar_url' => 'nullable|file|max:2048',
        ];
    }
}
