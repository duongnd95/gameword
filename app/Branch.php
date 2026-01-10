<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Branch extends Model
{
    use Sortable;

    protected $table = 'branches';

    protected $primaryKey = 'id';

    public $sortable = ['id',
        'name',
        'phone',
        'email',
        'address',
        'birthday',
        'updated_at'
    ];

    protected $fillable = ['name','email','address','birthday','phone','city_id'];

    public function user(){
        return $this->hasMany('App\User');
    }

    public function city(){
        return $this->belongsTo('App\City','city_id');
    }
}
