<?php

namespace Modules\Booking\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class BookingDetailResource extends Resource
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
            "id" => $this->bookingable->id,
		    "price" => number_format($this->price),
		    "name" => $this->bookingable->name,
		    $this->mergeWhen(isset($this->bookingable->elective_journey), [
			    'elective_journey' => $this->bookingable->elective_journey
		    ]),
		    $this->mergeWhen($this->bookingable->start_time, [
			    'start_time' => $this->bookingable->start_time
		    ]),
		    $this->mergeWhen($this->bookingable->journey, [
			    'journey' => $this->bookingable->journey
		    ]),
	    ];
    }
}
