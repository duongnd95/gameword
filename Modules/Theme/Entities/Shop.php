<?php

namespace Modules\Theme\Entities;

use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "shops";

    protected $sortable = [
        'name',
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
    protected $fillable = ['name','address','phone', 'image', 'opening_hours', 'latitude', 'longitude', 'active','arrange'];

    static public function uploadAndResize($image, $width = 450, $height = null){
        if(empty($image)) return;
        $folder = "/images/shops/";
        if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').$folder);
        }
        //getting timestamp
        $timestamp = Carbon::now()->toDateTimeString();
        $fileExt = $image->getClientOriginalExtension();
        $filename = str_slug(basename($image->getClientOriginalName(), '.'.$fileExt));
        $pathImage = str_replace([' ', ':'], '-', $folder.$timestamp. '-' .$filename.'.'.$fileExt);

        $img = \Image::make($image->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save(storage_path('app/public').$pathImage);

        return config('filesystems.disks.public.path').$pathImage;
    }
}
