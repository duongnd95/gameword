<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class AttributeProduct extends Model
{
    use Cachable;
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_attributes';

    public $sortable = [
        'type',
        'name',
        'product_id',
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
    protected $fillable = ['key', 'value', 'active'];

   
    public function product()
    {
        return $this->belongsToMany('Modules\Product\Entities\Product', 'product_id');
    }

    public static function getListAttrs()
    {
        $arr = ['color' => 'Màu sắc', 'size' => 'Kích cỡ', 'material' => 'Chất liệu', 'weight' => 'Cân nặng'];
        return $arr;
    }
}
