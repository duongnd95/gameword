<?php

namespace Modules\Booking\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class CustomerResource extends Resource
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
		    'id' => $this->id,
            'name' => $this->name,
		    'email' => $this->email,
		    'phone' => $this->phone,
		    'phone_other' => $this->phone_other,
		    'gender' => $this->gender,
		    'gender_text' => $this->textGender,
		    'address' => $this->address,
		    'permanent_address' => $this->permanent_address,
		    'facebook' => $this->facebook,
		    'zalo' => $this->zalo,
	    ];
    }
}
