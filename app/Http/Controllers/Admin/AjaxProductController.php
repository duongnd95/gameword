<?php

namespace App\Http\Controllers\Admin;

use App\GalleryProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AjaxProductController extends Controller
{
    public function deleteGallery($id, Request $request)
    {
        $delete = GalleryProduct::findOrFail($id);
        $data = GalleryProduct::where('product_id', $id)->get();
        $image_small = public_path() . '/storage/images/gallery-products/small/' . $delete->image;
        $image_large = public_path() . '/storage/images/gallery-products/thumb/' . $delete->image;
        //xóa hình ảnh theo đường dẫn
        if ($delete->delete()) {
            unlink($image_small);
            unlink($image_large);
        }
         $delete->delete();
         // Return response
         return response()->json([
                'data' => $data,
                'message' => 'Hình ảnh đã được xóa!',
        ]);
    }
    /*Upload image*/
    public function uploadAlbums(Request $request){
        $file = $request->file('file');
        if ($path_image = GalleryProduct::saveImageResize($file)){
            $info['name'] = $path_image;
            $info['src'] = asset(\Storage::url($path_image));
            return \response()->json($info);
        }
        return \response()->json('error');
    }
    public function deleteAlbums(Request $request){
        $folder = $request->folder;
        GalleryProduct::deleteFile($folder);

        return \Response::json('success', 200);
    }
}
