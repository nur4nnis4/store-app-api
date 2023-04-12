<?php

namespace App\Http\Requests;

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
            'id' => 'required|max:36|unique:users',
            'name' => 'required|max:40',
            'email' => 'required|max:255|email|unique:users',
            'password' => 'required|min:4',
            'address' => '',
            'phone_number' => 'unique:users|regex:/^\+?\d{10,16}$/',
            'photo_url' => 'image',
        ];
    }
}
