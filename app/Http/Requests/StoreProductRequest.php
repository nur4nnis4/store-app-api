<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => "required|max:36|unique:products",
            'name' => "required|max:100",
            'price' => "required|numeric",
            'brand' => "required|max:50",
            'category' => "required|max:50",
            'description' => "required",
            'image_url' => "required|url",
            'is_popular' => "required|boolean",
            'quantity' => "required|numeric",
            'sales' => "required|numeric",
        ];
    }
}
