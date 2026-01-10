<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneCall extends Model
{
    protected $table = 'phone_calls';
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
    protected $fillable = ['phone', 'check_call', 'user_update_id'];

    public function user(){
        return $this->belongsTo('App\User', 'user_update_id');
    }
}
