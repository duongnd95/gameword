<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class AboutUs extends Model
{
    use Sortable, SoftDeletes;
    protected $table = 'about_us';

    public $sortable = [
        'title',
        'updated_at'
    ];

    protected $fillable = ['title', 'icon', 'color', 'content', 'active'];

    public static function getListActive(){
        $arr = [1 => 'Hiển thị', 2 => 'Không hiển thị'];
        return $arr;
    }
}
