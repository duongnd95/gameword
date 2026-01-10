<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Str;

class Page extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pages';

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

    protected $fillable = ['name','banner','description','content', 'parent_id','slug', 'postion', 'active','avatar'];

    static public function uploadAndResize($image, $width = 769, $height = null){
        if(empty($image)) return;
        $folder = "/images/pages/";
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

    public function parent(){
        return $this->belongsTo('App\Page','parent_id');
    }

    // BƯỚC 2: HÀM ĐỆ QUY HIỂN THỊ Page
    public static function showPages($pages, $pageId = null, $parent_id = 0, $char = '')
    {
        foreach ($pages as $key => $value) {
            $selected = $pageId == $value->id ? 'selected' : '';
            // Nếu là chuyên mục cha thì hiển thị
            if ($value->parent_id == $parent_id) {
                echo '<option value="' . $value->id . '" ' . $selected . '>';
                echo $char . $value->name;
                echo '</option>';
                // Xóa chuyên mục đã lặp
                unset($pages[$key]);
                // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
                self::showPages($pages, $pageId, $value->id, $char . '-');
            }

        }

    }

    //Postion
    public static function getPostionPages(){
        $arr = [0 => 'Main Page', 1 => 'Left Footer', 2 => 'Center Footer', 3 => 'Right Footer'];
        return $arr;
    }

    public function showListPages($pages, $parent_id = '', $level = ''){
        foreach ($pages as $key => $item){
            if ($item['parent_id'] == $parent_id){
                echo '<tr>';
                //echo '<td class="text-center"><input type="checkbox" name="chkId" id="chkId" value="'.$item->id.'" data-id="'.$item->id.'"/></td>';
                echo '<td>'.$level.$item['name'].'</td>';
                echo '<td>'.$item['slug'].'</td>';
                echo '<td>'.Str::limit($item['description'],30).'</td>';
//                echo '<td class="text-center">'.(($item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : '')).'</td>';
                echo '<td class="text-center">'.Carbon::parse($item->updated_at)->format(config('settings.format.date')).'</td>';
                echo '<td class="text-center">';
                if(\Auth::user()->can("PageController@show"))
                {
                    echo '<a href="'.url('/pages/' . $item->id) .'" title="'.trans("message.view").'"><button class="btn btn-info btn-xs" style="margin-right: 5px;"><i class="fa fa-eye" aria-hidden="true"></i></button></a>';
                }

                if(\Auth::user()->can("PageController@update"))
                {
                    echo '<a href="'.url('/pages/' . $item->id . '/edit').'" title="'.trans("message.edit").'"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>';
                }
                echo '</td>';
                echo '</tr>';
                unset($pages[$key]);
                $this->showListPages($pages, $item['id'], $level.'-- ');
            }
        }
    }
    public static function getPages($treePages){
        $pages = self::getListPageToArray($treePages);
        $pages = \Arr::prepend(!empty($pages) ? $pages : [], __('message.please_select'), '');
        return $pages;
    }
}
