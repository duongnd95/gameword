<?php

namespace Modules\Product\Entities;


use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class GalleryProduct extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "gallery_products";
    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $fillable = ['image', 'products_id'];

    public function product()
    {
        return $this->belongsTo('Modules\Product\Entities\Product');
    }
}
