<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'id';

    protected $fillable = ['type_id', 'title', 'slug', 'position', 'arrange','parent_id'];

    public static function getListTypePage(){
        $arr = ['Trang danh mục', 'Trang chi tiết'];
        return $arr;
    }

    public function getMenu($linkMod){
        $modules = \Module::all();
        $mod = [];
        foreach ($modules as $index=>$item){
            if ($item->active == 1)
                if ($item->name != 'Booking')
                    if (!empty(config($item->alias . '.' . $linkMod)))
                    $mod[] = config($item->alias . '.' . $linkMod);
        }
        return $mod;
    }

    public function parent(){
        return $this->belongsTo('App\Menu','parent_id');
    }
}
