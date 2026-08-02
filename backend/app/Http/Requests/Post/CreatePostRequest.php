<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images'    => ['required', 'array', 'min:1', 'max:10'],
            'images.*'  => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'caption'   => ['nullable', 'string' ,'max:2000'],
        ];
    }
}
