<?php

namespace Modules\Product\Entities;

use App\Color;
use App\Gift;
use App\ResizeImage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class Product extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    public $sortable = [
        'name',
        'price',
        'price_compare',
        'category_id',
        'updated_at',
    ];

     // Cast attributes JSON to array
     protected $casts = [
        'attributes' => 'array'
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
    protected $fillable = ['name', 'slug', 'image', 'description', 'content', 'active', 
            'price','price_compare',
            'category_id','provider_id', 'related', 'together', 'new', 'hot'];

    public function category()
    {
        return $this->belongsTo('Modules\Product\Entities\CategoryProduct');
    }


    public function provider()
    {
        return $this->belongsTo('Modules\Product\Entities\ProviderProduct');
    }

    public function color()
    {
        return $this->belongsToMany(Color::class, 'product_color');
    }
    public function gift()
    {
        return $this->belongsToMany(Gift::class, 'product_gift');
    }

    public function bookings(){
        return $this->morphToMany('Modules\Booking\Entities\Booking', 'bookingable', 'booking_detail');
    }
    public function review()
    {
        return $this->hasMany('Modules\Product\Entities\Review');
    }

    public function gallery()
    {
        return $this->hasMany('Modules\Product\Entities\GalleryProduct', 'product_id');
    }

    public static function getInventory()
    {
        $arr = [0 => 'Không quản lý tồn kho', 1 => 'Có quản lý tồn kho'];
        return $arr;
    }

    static public function uploadAndResize($image, $width = 300, $height = null){
        if(empty($image)) return;
        $folder = "/images/products/";
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

    static public function uploadAndResizeGift($image, $width = 100, $height = null){
        if(empty($image)) return;
        $folder = "/images/gifts/";
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

    static public function uploadAndResizeColor($image, $width = 100, $height = null){
        if(empty($image)) return;
        $folder = "/images/colors/";
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

    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($product) { // before delete() method call this
            //\File::delete(optional($product->gallery)->image);
            $product->gallery()->delete();
        });
    }
    
}