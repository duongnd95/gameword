<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Contact extends Model
{
    use Sortable;

    protected $table = 'contacts';
    protected $primaryKey = 'id';
    protected $sortable = [
        'fullname',
        'email',
        'updated_at'
    ];
    protected $fillable = ['fullname','email','address','phone','message'];
}
