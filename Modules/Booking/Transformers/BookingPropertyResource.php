<?php

namespace Modules\Booking\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class BookingPropertyResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            $this->key => $this->pivot->value
        ];
    }
}
