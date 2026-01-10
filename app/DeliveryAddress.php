<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{

    protected $table = 'address_delivery';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'province_id', 'district_id', 'ward_id', 'is_default', 'is_export_invoice',
        'tax_code', 'company_name', 'company_address', 'company_email', 'phone', 'money_ship', 'distance', 'user_id'];

    public function province()
    {
        return $this->belongsTo('App\Province');
    }

    public function district()
    {
        return $this->belongsTo('App\District');
    }

    public function ward()
    {
        return $this->belongsTo('App\Ward');
    }
}
