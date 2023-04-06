<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductDetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'brand' => $this->brand,
            'category' => $this->category,
            'description' => $this->description,
            'image_url' => Storage::url('app/public/' . $this->image), //Make sure APP_URL in env file is accurate
            'is_popular' => $this->is_popular,
            'stock' => $this->stock,
            'sales' => $this->price,
            'seller' => $this->whenLoaded('seller'),
        ];
    }
}
