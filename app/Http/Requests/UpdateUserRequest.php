<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'max:40',
            'address' => '',
            'phone_number' => 'numeric',
            'photo_url' => 'image',
        ];
    }
}
