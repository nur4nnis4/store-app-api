<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'image_url' => $this->image,
            'is_popular' => $this->is_popular,
            'stock' => $this->stock,
            'sales' => $this->price,
            'seller_id' => $this->price,
        ];
    }
}
