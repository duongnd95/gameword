<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewProductResource extends JsonResource
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
            "email" => $this->email,
            "rating" => $this->rating,
            "review" => $this->review,
            "product" => optional($this->product)->name,
            "created_at" => $this->created_at
        ];
    }
}
