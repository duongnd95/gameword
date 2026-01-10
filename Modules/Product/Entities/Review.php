<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;



class Review extends Model
{
    use Sortable;
    /**
    * The database table used by the model.
    *
    * @var string
    */
   protected $table = "reviews";

   protected $sortable = [
    'name',
    'email',
    'updated_at'
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
   protected $fillable = ['name','email','rating','review','product_id','title'];

   public function product()
    {
        return $this->belongsTo('Modules\Product\Entities\Product');
    }


}
