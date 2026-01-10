<?php

namespace Modules\Booking\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Booking\Entities\BookingProperty;

class BookingResource extends Resource
{
	public static function collection($resource)
	{
		return tap(new BookingCollection($resource), function ($collection) {
			$collection->collects = __CLASS__;
		});
	}
	/**
	 * @var array
	 */
	protected $withoutFields = [];
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request
	 * @return array
	 */
	public function toArray($request)
	{
//	    dump($this->properties);
		$properties = [];
		foreach ($this->properties as $property){
			$properties[$property->key] = $property->pivot->value;
		}
		return $this->filterFields([
			"id" => $this->id,
			"code" => $this->code,
			"creator_id" => $this->creator_id,
			"customer" => new CustomerResource($this->customer),
			"total_price" => number_format($this->total_price),
			"note" => $this->note,
			"cancel_note" => $this->cancel_note,
			"approve_id" => $this->approve_id,
			"created_at" => $this->created_at->toDateTimeString(),
			"updated_at" => $this->updated_at->toDateTimeString(),
			"deleted_at" => $this->deleted_at ? $this->deleted_at->toDateTimeString() : $this->deleted_at,
			"properties" =>$properties,
			"products" => new BookingDetailResource($this->detail)
		]);
	}
	/**
	 * Set the keys that are supposed to be filtered out.
	 *
	 * @param array $fields
	 * @return $this
	 */
	public function hide(array $fields)
	{
		$this->withoutFields = $fields;
		return $this;
	}
	/**
	 * Remove the filtered keys.
	 *
	 * @param $array
	 * @return array
	 */
	protected function filterFields($array)
	{
		return collect($array)->forget($this->withoutFields)->toArray();
	}

}
