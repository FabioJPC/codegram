<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string', 'min:1', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'O comentário não pode ser enviado em branco.',
            'comment.max'      => 'O comentário pode ter no máximo 1000 caracteres.',
        ];
    }
}
