<?php

namespace Modules\Product\Http\Controllers;

use App\Color;
use App\Gift;
use App\ResizeImage;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Modules\Product\Entities\GalleryProduct;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\CategoryProduct;
use Modules\Product\Entities\ProviderProduct;
use Modules\Product\Transformers\ProductResource;
use Modules\Booking\Entities\BookingItem;
use Session,Image,Alert;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $keyword = $request->get('search');
        $perPage = config('settings.perpage');

        $product = new Product();

        if (!empty($keyword)) {
            $product = $product->where('name', 'LIKE', "%$keyword%");
        }

        if(!empty($request->get('category'))){
            $product = $product->where('category_id', $request->get('category'));
        }


        $product = $product->with('category');

        $product = $product->sortable(['updated_at' => 'desc'])->paginate($perPage);
        return view('product::products.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Lấy tất cả thể loại sản phẩm
        $categories = CategoryProduct::all()->pluck('name', 'id');
        $categories->prepend(__('message.please_select'), '')->all();
       //Lấy tất cả thương hiệu sản phẩm
        $providers = ProviderProduct::all()->pluck('name', 'id');
        $providers->prepend(__('message.please_select'), '')->all();

        //Sắp xếp
        $arrange = Product::max('arrange');
        $arrange = empty($arrange) ? 1: $arrange+1;

        return view('product::products.create', compact('categories', 'arrange', 'providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        if (empty($request->get('slug'))) {
            $requestData['slug'] = str_slug($requestData['name']);
        }

        //Kiểm tra tình trạng
        if(!isset($request->active)){
            $requestData["active"] = config("settings.inactive");
        }

        if(!empty($request->related)){
            $requestData['related'] = json_encode($requestData['related']);
        }

        if(!empty($request->together)){
            $requestData['together'] = json_encode($requestData['together']);
        }

        \DB::transaction(function () use ($request, $requestData) {
            if ($request->hasFile('image')) {
                $requestData['image'] = Product::uploadAndResize($request->file('image'));
            }
            $requestData['price'] = (double)str_replace(',','',$request->price);
            $requestData['price_compare'] = !empty($request->price_compare) ? (double)str_replace(',','',$request->price_compare) : null;
            $product = Product::create($requestData);
            //Insert Gifts
            if(!empty($requestData['gifts'])){
                foreach ($requestData['gifts'] as $key => $val) {
                    $val['image'] = Product::uploadAndResizeGift($val['image']);
                    $gift = Gift::create($val);
                    $product->gift()->attach($gift->id);
                }
            }
            //Insert Colors
            if(!empty($requestData['colors'])){
                foreach ($requestData['colors'] as $key => $val) {
                    $color = Color::create([
                        'name' => $val['name']
                    ]);
                    $product->color()->attach($color->id);
                    foreach($val['image'] as $file){
                        $val['image'] = Product::uploadAndResizeColor($file);
                        $color->images()->create([
                            'image' => $val['image'],
                            'color_id' => $color->id
                        ]);
                    }
                }
            }
            //Multifile upload
            if (!empty($requestData['images'])){
                if (count($requestData["images"]) > 0) {
                    foreach ($requestData['images'] as $file) {
                        $image_galleries = Product::uploadAndResize($file);
                        $product->gallery()->create([
                            'image' => $image_galleries,
                            'product_id' => $product->id,
                        ]);
                    }
                }
            }
        });

        toastr()->success(__('product::products.created_success'));

        return redirect('admin/products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $product = Product::findOrFail($id);

        //Lấy đường dẫn cũ
        $backUrl = $request->get('back_url');
        return view('product::products.show', compact('product', 'backUrl'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $product = Product::findOrFail($id);

        $relatedId = json_decode(Product::where('id', $id)->pluck('related')->first());

        $related = Product::whereIn('id', $relatedId??[])->pluck('id', 'name');

        $togetherId = json_decode(Product::where('id', $id)->pluck('together')->first());
        $together = Product::whereIn('id', $togetherId??[])->pluck('id', 'name');

        //Lấy tất cả thể loại sản phẩm
        $categories = CategoryProduct::all()->pluck('name', 'id');
        $categories->prepend(__('message.please_select'), '')->all();
        //Lấy tất cả thương hiệu sản phẩm
        $providers = ProviderProduct::all()->pluck('name', 'id');
        $providers->prepend(__('message.please_select'), '')->all();

        $backUrl = $request->get('back_url');
        return view('product::products.edit', compact('product', 'categories','providers',
            'backUrl', 'together', 'related'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $requestData = $request->all();
        if (empty($request->get('slug'))) {
            $requestData['slug'] = str_slug($requestData['name']);

        }
        //Kiểm tra tình trạng
        if(!isset($request->active)){
            $requestData["active"] = config("settings.inactive");
        }
        if(!isset($request->hot)){
            $requestData["hot"] = config("settings.inactive");
        }
        if(!isset($request->new)){
            $requestData["new"] = config("settings.inactive");
        }
        if(!empty($request->related)){
            $requestData['related'] = json_encode($requestData['related']);
        }

        if(!empty($request->together)){
            $requestData['together'] = json_encode($requestData['together']);
        }

        \DB::transaction(function () use ($request, $requestData, $product) {
            if ($request->hasFile('image')) {
                //ResizeImage::deleteOldImage($product->image);
                \File::delete($product->image);
                $requestData['image'] = Product::uploadAndResize($request->file('image'));
            }
            //Multifile upload
            if (!empty($requestData['images'])){
                if (count($requestData["images"]) > 0) {
//                    $productGallery = GalleryProduct::where('product_id', $product->id)->get();
//                    if (!empty($productGallery) && $productGallery->count() > 0){
//                        foreach ($productGallery as $file){
//                            \File::delete($file->image);
//                            GalleryProduct::where('id', $file->id)->delete();
//                        }
//                    }
                    //dd($requestData['images']);
                    foreach ($requestData['images'] as $file) {
                        if(!starts_with($file, "storage") && !starts_with($file, "[object File]")){
                            $image_galleries = Product::uploadAndResize($file);
                            $product->gallery()->create([
                                'image' => $image_galleries,
                                'product_id' => $product->id
                            ]);
                        }
                    }

                }
            }
            $requestData['price'] = (double)str_replace(',','',$request->price);
            $requestData['price_compare'] = !empty($request->price_compare) ? (double)str_replace(',','',$request->price_compare) : null;
            $product->update($requestData);

        });

        toastr()->success(__('products.updated_success'));


        if ($request->has('back_url')) {
            $backUrl = $request->get('back_url');
            if (!empty($backUrl)) {
                return redirect($backUrl);
            }
        }

        return redirect('admin/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if (!empty($product->image)) {
            \File::delete($product->image);
        }
        Product::destroy($id);

        toastr()->success(__('product::products.deleted_success'));

        return redirect('admin/products');
    }
}