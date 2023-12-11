<?php

namespace App\Http\Requests;

class LoginRequest extends CustomFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => "required|email|string",
            'password' => "required:string"
        ];
    }
}
