<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class GroupProduct extends Model
{
    use Cachable;
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "group_products";

    protected $sortable = [
        'name',
        'created_at',
        'updated_at',
    ];

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
    protected $fillable = ['name','slug', 'active'];

    public function products()
    {
        return $this->hasMany('Modules\Product\Entities\Product');
    }

    
}
