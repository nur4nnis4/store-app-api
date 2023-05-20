<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
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
            'is_popular' => $this->is_popular == 1 ? true : false,
            'stock' => (int) $this->stock,
            'sales' => (int) $this->sales,
            'created_at' => $this->created_at,
            'seller' => new UserResource($this->whenLoaded('seller')),
        ];
    }
}
