<?php

namespace Modules\Booking\Entities;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class BookingDetail extends Model
{
	use Cachable;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'booking_detail';

	/**
	 * The database primary key value.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';
	public $timestamps = false;

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['price', 'quantity', 'booking_id', 'bookingable_id', 'bookingable_type'];

	public function bookingable()
	{
		return $this->morphTo();
	}

}
