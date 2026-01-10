<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $table = 'colors';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'price', 'image'];

    public function products()
    {
        return $this->belongsToMany('App\Product', 'product_color');
    }
    public function images()
    {
        return $this->hasMany('App\ColorImage','color_id');
    }
}
