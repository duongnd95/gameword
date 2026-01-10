<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Newsletter extends Model
{
    use Sortable;

    protected $table = 'newsletters';

    protected $sortable = [
        'email',
        'created_at'
    ];

    protected $fillable = ['email'];

    protected $primaryKey = 'id';
}
