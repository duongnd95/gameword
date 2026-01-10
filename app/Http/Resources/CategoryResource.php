<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            "description" => $this->description,
            "parent_id" => $this->parent_id,
            "parent_name" => optional($this->parent)->name,
            "arrange" => $this->arrange,
            "created_at" => $this->created_at,
        ];
    }
}
