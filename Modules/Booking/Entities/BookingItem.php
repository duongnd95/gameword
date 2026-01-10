<?php

namespace Modules\Booking\Entities;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class BookingItem extends Model
{
    use Cachable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'booking_items';

     /**
     * The database primary key value.
     *
     * @var string
     */
     protected $primaryKey = 'id';

     /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['quantity', 'product_id', 'booking_id'];

  
}
