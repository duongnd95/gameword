<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeoProduct extends Model
{
    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $table = 'seo_ecommerce';
    
    protected $primaryKey = 'id';

    protected $fillable = ['meta_title', 'meta_keyword', 'meta_description', 'slug', 'type_id'];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
