<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\News;
use App\SysMenu;
use Illuminate\Support\Str;

class NewsController extends Controller
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

        $news = new News();
        $active = News::getListActive();

        if (!empty($keyword)){
            $news = $news->where('title','LIKE',"%$keyword%");
        }


        $news = $news->with('category');

        $news = $news->sortable(['updated_at' => 'desc'])->paginate($perPage);
        return view('admin.news.index', compact('news', 'active'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::getCategories(Category::all());
        return view('admin.news.create',compact('categories'));
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
        if (empty($request->get('slug'))){
            $requestData['slug'] = Str::slug($requestData['title']);
        }

        $requestData['slug'] = SysMenu::setTrueSlug($requestData['slug']);

        \DB::transaction(function () use ($request, $requestData){
            if ($request->hasFile('image')) {
                $requestData['image'] = News::uploadAndResize($request->file('image'));
            }
            if (empty($requestData['active'])){
                $requestData['active'] = 0;
            }
            News::create($requestData);
        });
        toastr()->success(__('theme::news.created_success'));

        return redirect('admin/news');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $news = News::findOrFail($id);

        if ($request->wantsJson()){
            return new NewsResource($news);
        }
        if($request->is('api/*')){
            return view('api.content', ['item' => $news]);
        }
        //Lấy đường dẫn cũ
        $backUrl = $request->get('back_url');
        return view('admin.news.show', compact('news', 'backUrl'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $news = News::findOrFail($id);
        $categories = Category::getCategories(Category::all());
        $backUrl = $request->get('back_url');
        return view('admin.news.edit', compact('news','categories', 'backUrl'));
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
        $news = News::findOrFail($id);

        $requestData = $request->all();
        if (empty($request->get('slug'))){
            $requestData['slug'] = Str::slug($requestData['title']);
            $requestData['slug'] = SysMenu::setTrueSlug($requestData['slug']);
        }

        if (empty($requestData['active'])){
            $requestData['active'] = 0;
        }


        \DB::transaction(function () use ($request, $requestData, $news){
            if($request->hasFile('image')) {
                \File::delete($news->image);
                $requestData['image'] = News::uploadAndResize($request->file('image'));
            }
            $news->update($requestData);
        });

        toastr()->success(__('theme::news.updated_success'));

        if ($request->has('back_url')){
            $backUrl = $request->get('back_url');
            if (!empty($backUrl)){
                return redirect($backUrl);
            }
        }

        return redirect('admin/news');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $new = News::findOrFail($id);
        if (!empty($new->image)){
            \File::delete($new->image);
        }
        News::destroy($id);
        toastr()->success( __('theme::news.deleted_success'));

        return redirect('admin/news');
    }
}
