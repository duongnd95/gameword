<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class SeoProduct extends Model
{
    use Cachable;
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
        return $this->belongsTo('Modules\Product\Entities\Product');
    }
}
