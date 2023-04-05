<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => "max:100",
            'price' => "numeric",
            'brand' => "max:50",
            'category' => "max:50",
            'image_url' => "url",
            'is_popular' => "boolean",
            'quantity' => "numeric",
            'sales' => "numeric",
        ];
    }
}
