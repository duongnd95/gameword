<?php

namespace Modules\Product\Entities;

use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class CategoryProduct extends Model
{
    use Cachable;
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "category_products";

    protected $sortable = [
        'name',
        'updated_at',
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
    protected $fillable = ['name', 'slug', 'image', 'description', 'parent_id', 'active'];

    public function products()
    {
        return $this->hasMany('Modules\Product\Entities\Product');
    }
    public function parent(){
        return $this->belongsTo('Modules\Product\Entities\CategoryProduct','parent_id');
    }

    static public function uploadAndResize($image, $width = 450, $height = null){
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

        $img = \Image::make($image->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save(storage_path('app/public').$pathImage);

        return config('filesystems.disks.public.path').$pathImage;
    }


    // BƯỚC 2: HÀM ĐỆ QUY HIỂN THỊ CATEGORIES
    public static function showCategories($categories, $categoryId = null, $parent_id = 0, $char = '')
    {
        foreach ($categories as $key => $value) {
            $selected = $categoryId == $value->id ? 'selected' : '';
            // Nếu là chuyên mục cha thì hiển thị
            if ($value->parent_id == $parent_id) {
                echo '<option value="' . $value->id . '" ' . $selected . '>';
                echo $char . $value->name;
                echo '</option>';
                // Xóa chuyên mục đã lặp
                unset($categories[$key]);
                // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
                self::showCategories($categories, $categoryId, $value->id, $char . '-');
            }

        }

    }
}
