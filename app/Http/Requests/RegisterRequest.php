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
            'username' => 'required|string|max:255|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'region' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'Toto uživatelské jméno je již zabrané.',
            'email.unique' => 'Tento email je již použitý.',
            'password.min' => 'Heslo musí mít alespoň 8 znaků.',
            'password.confirmed' => 'Hesla se neshodují.',
        ];
    }
}
