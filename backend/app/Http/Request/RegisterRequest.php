<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).+$/',
            ],
        ];
    }

    public function messages()
{
    return [
        'name.required' => 'Tên là bắt buộc',
        'email.required' => 'Email là bắt buộc',
        'password.required' => 'Mật khẩu là bắt buộc',
        'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        'password.regex' => 'Mật khẩu phải bao gồm ít nhất 1 chữ hoa, 1 chữ thường và 1 ký tự đặc biệt',
    ];
}
}
