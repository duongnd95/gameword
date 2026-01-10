<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            "title" => $this->title,
            "image" => asset(\Storage::url($this->image)),
            "category" => optional($this->category)->title,
            "category_id" => $this->category_id,
            "description" => $this->description,
            "content" => $this->content,
            "created_at" => $this->created_at->toDateTimeString(),
        ];
    }
}
