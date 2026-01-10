<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceNotify extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'device_notifies';

	/**
	 * The database primary key value.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';

	static public $TYPE_WEB = 'website';
	static public $TYPE_MOBILE = 'app';

	/**
	 * Attributes that should be mass-assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['token', 'device_type', 'device_info', 'user_id'];

	public function user(){
		return $this->belongsTo('App\User');
	}
	public static function boot()
	{
		parent::boot();
		static::creating(function ($modal) {

		});

		static::updated(function ($modal) {
			
		});

		static::saving(function ($modal) {

		});
		static::deleted(function ($modal) {
		
		});
	}
}
