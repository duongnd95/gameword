<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Promotion extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'promotions';

    public $sortable = [
        'title',
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
    protected $fillable = ['title','slug','avatar','banner','date_start','date_end','positive','content'];

    static public function uploadAndResize($image, $width = 769, $height = null){
        if(empty($image)) return;
        $folder = "/images/promotion/";
        if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').$folder);
        }
        //getting timestamp
        $timestamp = Carbon::now()->toDateTimeString();
        $fileExt = $image->getClientOriginalExtension();
        $filename = str_slug(basename($image->getClientOriginalName(), '.'.$fileExt));
        $pathAvatar = str_replace([' ', ':'], '-', $folder.$timestamp. '-' .$filename.'.'.$fileExt);
        \Image::make($image->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('/storage').$pathAvatar);

        return config('filesystems.disks.public.visibility').$pathAvatar;
    }
	public static function boot()
	{
		parent::boot();
	}
}
