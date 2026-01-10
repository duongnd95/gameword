<?php

namespace Modules\Booking\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ApproveResource extends Resource
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
			'color' => $this->color,
		];
	}
}
