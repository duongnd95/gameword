<?php

namespace Modules\Booking\Entities;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class PaymentMethod extends Model
{
    use Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'credit_banks';

    public $sortable = [
        'id',
        'name',
        'updated_at',
        'created_at',
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
    protected $fillable = ['name', 'arrange', 'image', 'active', 'type'];

    static public function uploadAndResize($image, $width = 100, $height = null){
        if(empty($image)) return;
        $folder = "/images/banks/";
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

    public function bookings(){
        return $this->hasMany('Modules\Booking\Entities\Booking', 'payment_id');
    }
}
