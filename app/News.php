<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class News extends Model
{
    use Sortable;

    const ID_NEWS = 2;

    static public $ID_NEWS = 1;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'news';

    public $sortable = [
        'title',
        'category_id',
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
    protected $fillable = ['title','category_id','slug','image', 'description','content','active', 'file'];

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function creator(){
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function editor(){
        return $this->belongsTo('App\User', 'editor_id');
    }

    public static function getListActive(){
        $arr = [1 => 'Hiển thị', 2 => 'Không hiển thị'];
        return $arr;
    }

    public static function uploadAndResize($image, $width = 450, $height = null)
    {
        if (!$image) return null;

        // ❗ KHÔNG có dấu / ở đầu
        $folder = 'images/news';

        // ✅ ĐÚNG disk
        $disk = \Storage::disk('public');

        if (!$disk->exists($folder)) {
            $disk->makeDirectory($folder);
        }

        $timestamp = now()->format('Y-m-d_H-i-s');
        $ext = $image->getClientOriginalExtension();
        $filename = str_slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));

        $path = $folder.'/'.$timestamp.'-'.$filename.'.'.$ext;

        $img = \Image::make($image->getRealPath());

        if ($height) {
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        // ✅ Lưu đúng vào storage/app/public
        $img->save($disk->path($path));

        // ✅ URL public
        return asset('storage/'.$path);
    }


    static public function uploadAndResizeOld($image, $width = 450, $height = null){
        if(empty($image)) return;
        $folder = "/images/news/";
        if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').$folder);
        }
        //getting timestamp
        $timestamp = Carbon::now()->toDateTimeString();
        $fileExt = $image->getClientOriginalExtension();
        $filename = str_slug(basename($image->getClientOriginalName(), '.'.$fileExt));
        $pathImage = str_replace([' ', ':'], '-', $folder.$timestamp. '-' .$filename.'.'.$fileExt);

        $img = \Image::make($image->getRealPath());
        // $img = \Image::make($image->getRealPath())->resize($width, $height, function ($constraint) {
        //     $constraint->aspectRatio();
        // });

        $img->save(storage_path('app/public').$pathImage);

        return config('filesystems.disks.public.path').$pathImage;
    }
    
    public static function insertAds($content)
    {
        // Check for patterns in the content and insert ad code accordingly
        // You can use regular expressions or any other method to identify where to insert ads
        $ads = \DB::table('settings')->where('key', 'google_mgi_news')->value('value');
        
        $ads2 = \DB::table('settings')->where('key', 'google_mgi_body_2')->value('value');
       
        
        if($ads2 != '' && $ads == ''){
            $inserted_text = $ads2;
            $position = strlen($content) / 2; // Insert at the middle
            $text = substr_replace($content, $inserted_text, $position, 0);
            return $text;
        }
        
        if($ads == null || $ads == '') return $content;
        
        // For example, inserting an ad after every second paragraph
        $paragraphs = explode('</p>', $content);
        $newContent = '';
        foreach ($paragraphs as $index => $paragraph) {
            $newContent .= $paragraph . '</p>';
            if (($index + 1) % 2 === 0) {
                $newContent .= '<div class="ad">'.$ads.'</div>';
            }
        }
        
        return $newContent;
    }

    public static function boot(){
        parent::boot();
        self::creating(function ($model){
            $model->creator_id = \Auth::user()->id;
            $model->editor_id = \Auth::user()->id;
        });
        self::updating(function ($model){
            $model->editor_id = \Auth::user()->id;
        });
    }
}