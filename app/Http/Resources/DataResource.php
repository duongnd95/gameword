<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Theme\Entities\Shop;

class DataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $shops = Shop::all();

        return [
            "products" => new ProductResource($this),
            "shops" => ShopResource::collection($shops)
        ];
    }
}
