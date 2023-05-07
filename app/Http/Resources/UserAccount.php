<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserAccount extends JsonResource
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
            'email' => $this->email,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'photo_url' => $this->photo_path ? (Storage::exists($this->photo_path) ? Storage::url('app/public/' . $this->photo_path) : $this->photo_path) : Storage::url('app/public/default/user.jpg'),
            'joined_at' => $this->created_at,
        ];
    }
}
