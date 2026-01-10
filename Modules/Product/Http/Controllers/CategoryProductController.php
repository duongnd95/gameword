<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\CategoryProduct;
use Modules\Theme\Entities\Menu;
use Session;

class CategoryProductController extends Controller
{
    use Authorizable;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = config('settings.perpage');

        $categories = CategoryProduct::sortable();

        if (!empty($keyword)) {
            $categories = CategoryProduct::where('name', 'LIKE', "%$keyword%");
        }

        $categories = $categories->paginate($perPage);
        return view('product::categories_product.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $categories = CategoryProduct::all();
        return view('product::categories_product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:category_products,name',
           
        ]);

        $requestData = $request->all();
        if (empty($request->get('slug'))) {
            $requestData['slug'] = str_slug($requestData['name']);
        }
         //kiểm tra tình trạng
         if(!isset($request->active)){
			$requestData["active"] = Config("settings.inactive");
        }
        //$requestData['slug'] = Menu::setTrueSlug($requestData['slug']);

        \DB::transaction(function () use ($request, $requestData) {
            if ($request->hasFile('image')) {
                $requestData['image'] = CategoryProduct::uploadAndResize($request->file('image'));
            }
            CategoryProduct::create($requestData);
        });

        toastr()->success(__('product::categories.created_success'));

        return redirect('admin/category-products');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $category = CategoryProduct::findOrFail($id);
        return view('product::categories_product.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $category = CategoryProduct::findOrFail($id);
        $categories = CategoryProduct::all();

        return view('product::categories_product.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'nullable',
        ]);
        $category = CategoryProduct::findOrFail($id);

        $requestData = $request->all();
        if (empty($request->get('slug'))) {
            $requestData['slug'] = str_slug($requestData['name']);
            $requestData['slug'] = Menu::setTrueSlug($requestData['slug']);
        }
        //kiểm tra tình trạng
         if(!isset($request->active)){
			$requestData["active"] = Config("settings.inactive");
        }
        \DB::transaction(function () use ($request, $requestData, $category) {
            if ($request->hasFile('image')) {
                \Storage::delete($category->image);
                $requestData['image'] = CategoryProduct::uploadAndResize($request->file('image'));
            }
            $category->update($requestData);
        });

        toastr()->success(__('product::categories.updated_success'));

        return redirect('admin/category-products');

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        CategoryProduct::destroy($id);

        toastr()->success(__('product::categories.deleted_success'));

        return redirect('admin/category-products');
    }
}