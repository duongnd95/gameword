<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "image" => $this->image,
            "address" => $this->address,
            "phone" => $this->phone,
            "opening_hours" => $this->opening_hours,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "created_at" => $this->created_at,
        ];
    }
}
