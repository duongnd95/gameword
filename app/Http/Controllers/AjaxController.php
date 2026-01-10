<?php
namespace App\Http\Controllers;

use App\Agent;
use App\CategoryGallery;
use App\Customer;
use App\Guide;
use App\Http\Requests;
use App\Journey;
use App\Menu;
use App\ModuleInfo;
use App\Tour;
use Carbon\Carbon;
use Modules\Product\Entities\Review;
use function foo\func;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
	/**
	 * Gọi ajax: sẽ gọi đến hàm = tên $action
	 * @param Request $action
	 * @param Request $request
	 * @return mixed
	 */
	public function index($action, Request $request)
	{
		return $this->{$action}($request);
	}

    public function deleteGallery(Request $request)
    {
        $id = $request->id;
        $gallery  = CategoryGallery::findOrFail($id);
        \File::delete($gallery->image);
        CategoryGallery::where('id',$id)->delete($id);
        return response()->json([
            'success' => 'ok'
        ]);
    }


    /*
     * DeleteMenus
     * @param: $request
     * */
    public function deleteMenus(Request $request){
        $ids = $request->ids;
        $arrId = explode(',', $ids,-1);
        foreach ($arrId as $item){
            \Modules\SysMenu\Entities\Menu::destroy($item);
        }
        return \response()->json(['success' => 'ok']);
    }


}