<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Coupon extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coupons';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

//    protected $casts = [
//        'user_id' => 'array'
//    ];

    public $sortable = [
        'name',
        'sale_price',
        'created_at',
        'expires_at',

    ];



    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'image', 'description','apply_target', 'type', 'invoice_status',
        'invoice_total', 'percent_off', 'max_sale', 'sale_price', 'active', 'expires_at', 'user_id'];

    static public function uploadAndResize($image, $width = 100, $height = null){
        if(empty($image)) return;
        $folder = "/images/coupons/";
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

    public static function getListApplyTarget()
    {
        $arr = ['1' => 'Tất cả khách hàng', '2' => 'Khách hàng cụ thể'];
        return $arr;
    }

    public static function getListType()
    {
        $arr = ['3' => 'Free Ship','2' => 'Giảm số tiền cụ thể','1' => 'Giảm theo %' ];
        return $arr;
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
