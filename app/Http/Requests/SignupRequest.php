<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'max:36',
            'name' => 'max:40',
            'email' => 'max:255|email',
            'address' => '',
            'phone_number' => 'max:15|numeric',
            'password' => 'min:4',
            'photo_url' => 'image',
        ];
    }
}
