<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class CategoryGallery extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "categories_gallery";


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
    protected $fillable = ['image','category_id'];


    public function category(){
        return $this->belongsTo('App\Category');
    }


    static public function uploadAndResize($image, $width = 1000, $height = null){
        if(empty($image)) return;
        $folder = "/images/categories/";
        if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').$folder);
        }
        //getting timestamp
        $timestamp = Carbon::now()->toDateTimeString();
        $fileExt = $image->getClientOriginalExtension();
        $filename = str_slug(basename($image->getClientOriginalName(), '.'.$fileExt));
        $pathImage = str_replace([' ', ':'], '-', $folder.$timestamp. '-' .$filename.'.'.$fileExt);

        $img = \Image::make($image->getRealPath());

        $img->save(storage_path('app/public').$pathImage);

        return config('filesystems.disks.public.path').$pathImage;
    }
}
