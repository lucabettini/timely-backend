<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as RulesPassword;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255|unique:users',
            'email' => 'required|max:255|email|unique:users',
            'password' => [
                'required',
                'confirmed',
                RulesPassword::min(8)->letters()->numbers()
            ],
            'timezone' => 'required|max:255'
        ];
    }
}
