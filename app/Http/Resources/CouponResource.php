<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            "apply_target" => $this->apply_target,
            "type" => $this->type,
            "invoice_status" => $this->invoice_status,
            "invoice_total" => $this->invoice_total,
            "percent_off" => $this->percent_off,
            "max_sale" => number_format($this->max_sale),
            "sale_price" => number_format($this->sale_price),
            "active" => $this->active,
            "expires_at" => $this->expires_at,
            "created_at" => $this->created_at,
        ];
    }
}
