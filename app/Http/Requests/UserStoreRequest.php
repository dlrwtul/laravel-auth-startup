<?php

namespace App\Http\Requests;

class UserStoreRequest extends CustomFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {

        return [
            'username' => 'required|string|between:2,50',
            'email' => 'required|string|email|max:50|unique:users',
            'roles' => 'required|exists:roles,name'
        ];
    }
}
