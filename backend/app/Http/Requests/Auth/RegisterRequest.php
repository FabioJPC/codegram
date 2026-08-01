<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password'      => ['required', 'confirmed', Password::min(8)->letters()->numbers()->symbols()],
            'username'      => ['required', 'string', 'min:3' ,'max:30', 'alpha_dash', Rule::unique('users', 'username')],
        ];
    }

    public function messages()
    {
        return
        [
            'name.required'         => 'O nome é obrigatório.',
            'name.string'           => 'O nome deve ser um texto válido.',
            'name.max'              => 'O nome pode ter no máximo 255 caracteres.',

            'email.required'        => 'O e-mail é obrigatório.',
            'email.email'           => 'Informe um e-mail válido.',
            'email.max'             => 'O e-mail pode ter no máximo 255 caracteres.',
            'email.unique'          => 'Este e-mail já está cadastrado.',

            'username.required'     => 'O nome de usuário é obrigatório.',
            'username.string'       => 'O nome de usuário deve ser um texto.',
            'username.min'          => 'O nome de usuário deve ter pelo menos 3 caracteres.',
            'username.max'          => 'O nome de usuário pode ter no máximo 30 caracteres.',
            'username.alpha_dash'   => 'O nome de usuário pode conter apenas letras, números, hífens e underlines.',
            'username.unique'       => 'Este nome de usuário já está em uso.',

            'password.required'     => 'A senha é obrigatória.',
            'password.confirmed'    => 'A confirmação da senha não confere.',
            'password.min'          => 'A senha deve ter pelo menos 8 caracteres.',
            'password.letters'      => 'A senha deve conter pelo menos uma letra.',
            'password.numbers'      => 'A senha deve conter pelo menos um número.',
            'password.symbols'      => 'A senha deve conter pelo menos um símbolo.',
        ];
    }
}
