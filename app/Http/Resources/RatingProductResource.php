<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Entities\Review;

class RatingProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $one_star = RatingProductResource::collection(optional($this->review)->where('rating', 1))->count();
        $two_star = RatingProductResource::collection(optional($this->review)->where('rating', 2))->count();
        $three_star = RatingProductResource::collection(optional($this->review)->where('rating', 3))->count();
        $four_star = RatingProductResource::collection(optional($this->review)->where('rating', 4))->count();
        $five_star = Review::where('product_id', $this->id)->where('rating', 5)->count();
        $rating_count = count(RatingProductResource::collection(optional($this->review)));

        return [
            "one_star_count" => $one_star,
            "two_star_count" => $two_star,
            "three_star_count" => $three_star,
            "four_star_count" => $four_star,
            "five_star_count" => $five_star,
            "avg_star" => optional($this->review)->avg('rating') ?? 0,
            "rating_count" => $rating_count,
        ];
    }
}
