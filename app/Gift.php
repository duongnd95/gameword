<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $table = 'gifts';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'price', 'image'];

    public function products()
    {
        return $this->belongsToMany('App\Product', 'product_gift');
    }
}
