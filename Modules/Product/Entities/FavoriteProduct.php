<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class FavoriteProduct extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'favorite_products';

    protected $fillable = ['product_id', 'user_id'];

    public function favoriteUsers()
    {
        return $this->belongsToMany('App\User', 'favorite_products','user_id','product_id');
    }
}
