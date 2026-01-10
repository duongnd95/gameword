<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\CategoryGallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SysMenu;
use App\Traits\Authorizable;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        // $perPage = config('settings.perpage');

        $categories = Category::sortable();

        if (!empty($keyword)){
            $categories = Category::where('title','LIKE',"%$keyword%");
        }

        $categories = $categories->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::getCategories(Category::all());
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:categories,title',
        ]);

        $requestData = $request->all();
        if (empty($request->get('slug'))){
            $requestData['slug'] = Str::slug($requestData['title']);
        }

        $requestData['slug'] = SysMenu::setTrueSlug($requestData['slug']);

        \DB::transaction(function () use ($request, $requestData){
            if ($request->hasFile('avatar')) {
                $requestData['avatar'] = Category::uploadAndResize($request->file('avatar'));
            }
            $category = Category::create($requestData);

            //Multifile upload
            if (!empty($requestData['images'])){
                if (count($requestData["images"]) > 0) {
                    foreach ($requestData['images'] as $file) {
                        $image_galleries = CategoryGallery::uploadAndResize($file);
                        $category->gallery()->create([
                            'image' => $image_galleries,
                            'category_id' => $category->id,
                        ]);
                    }
                }
            }
        });

        toastr()->success(__('theme::categories.created_success'));

        return redirect('admin/categories');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categories = Category::getCategories(Category::all());
        $category = Category::findOrFail($id);
        return view('admin.categories.show', compact('category','categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::getCategories(Category::all());
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category', 'categories'));
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
        $category = Category::findOrFail($id);

        $requestData = $request->all();
        if (empty($request->get('slug'))){
            $requestData['slug'] = Str::slug($requestData['title']);
            $requestData['slug'] = SysMenu::setTrueSlug($requestData['slug']);
        }
        if (empty($requestData['active'])){
            $requestData['active'] = config('settings.inactive');
        }
        \DB::transaction(function () use ($request, $requestData, $category){
            if($request->hasFile('avatar')) {
                \File::delete($category->image);
                $requestData['avatar'] = Category::uploadAndResize($request->file('avatar'));
            }
            //Multifile upload
            if (!empty($requestData['images'])){
                if (count($requestData["images"]) > 0) {
                    foreach ($requestData['images'] as $file) {
                        if(!starts_with($file, "storage") && !starts_with($file, "[object File]")){
                            $image_galleries = CategoryGallery::uploadAndResize($file);
                            $category->gallery()->create([
                                'image' => $image_galleries,
                                'category_id' => $category->id
                            ]);
                        }
                    }

                }
            }
            $category->update($requestData);
        });

        toastr()->success(__('theme::categories.updated_success'));
        return redirect('admin/categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::destroy($id);

        toastr()->success(__('theme::categories.deleted_success'));

        return redirect('admin/categories');
    }
}
