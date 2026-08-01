<?php

namespace App\Http\Requests\Profile;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => ['sometimes', 'string', 'max:255'],
            'bio'           => ['sometimes', 'nullable', 'string', 'max:500'],
            'profile_photo' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'email'=> [
                    'sometimes',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
            'username'=> [
                    'sometimes',
                    'string',
                    'min:3' ,
                    'max:30',
                    'alpha_dash',
                    Rule::unique('users', 'username')->ignore($this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser um texto válido.',
            'name.max' => 'O nome pode ter no máximo 255 caracteres.',

            'bio.string' => 'A biografia deve ser um texto válido.',
            'bio.max' => 'A biografia pode ter no máximo 500 caracteres.',

            'profile_photo.image' => 'O arquivo enviado deve ser uma imagem.',
            'profile_photo.mimes' => 'A foto de perfil deve estar nos formatos JPG, JPEG, PNG ou WEBP.',
            'profile_photo.max' => 'A foto de perfil pode ter no máximo 2 MB.',

            'email.email' => 'Informe um e-mail válido.',
            'email.max' => 'O e-mail pode ter no máximo 255 caracteres.',
            'email.unique' => 'Este e-mail já está cadastrado.',

            'username.string' => 'O nome de usuário deve ser um texto.',
            'username.min' => 'O nome de usuário deve ter pelo menos 3 caracteres.',
            'username.max' => 'O nome de usuário pode ter no máximo 30 caracteres.',
            'username.alpha_dash' => 'O nome de usuário pode conter apenas letras, números, hífens e underlines.',
            'username.unique' => 'Este nome de usuário já está em uso.',
        ];
    }

}
