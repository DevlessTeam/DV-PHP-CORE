<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RegisterUserRequest extends Request
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
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password'  => 'required|confirmed|min:6',
            'password_confirmation' => 'required|min:6',
            'app_name'  => 'required|max:255',
            'app_description'  => 'required|max:255'
        ];
    }
}
