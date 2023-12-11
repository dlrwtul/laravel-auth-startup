<?php

namespace App\Http\Requests;

class RegisterRequest extends CustomFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
        ];
    }
}
