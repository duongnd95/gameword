<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $gifts_id = [];
        foreach ($this->gift as $item) {
            //pivot attribute returns a model which represent user_role table
            $gifts_id[] = $item->pivot->gift_id;
        }
        return [
            "id" => $this->id,
            "name" => $this->name,
            "image" => $this->image,
            "image_source" => GalleryProductResource::collection(optional($this->gallery)),
            "price" => number_format($this->price),
            "price_compare" => number_format($this->price_compare),
            "description" => $this->description,
            "content" => $this->content,
            "star" => optional($this->review)->avg('rating'),
            "category " => new CategoryResource($this->category),
            "provider_id" => $this->provider_id,
            "provider" => optional($this->provider)->name,
            "gift_id" => $gifts_id,
            "gifts" => GiftResource::collection($this->gift),
            "colors" => ColorResource::collection($this->color),
            "sale_off" => round((($this->price_compare - $this->price)/$this->price_compare)*100, 2),
            "created_at" => Carbon::parse($this->created_at)->format('d/m/Y H:m')
        ];
    }
}
