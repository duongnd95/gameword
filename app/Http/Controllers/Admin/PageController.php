<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;
use App\SysMenu;

class PageController extends Controller
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
        $pages = Page::sortable();
        $postion = Page::getPostionPages();
        if (!empty($keyword)){
            $pages = $pages->where('name','LIKE',"%$keyword%");
        }

        $pages = $pages->paginate($perPage);
        return view('admin.pages.index', compact('pages', 'postion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pages = Page::all();
        $postion = Page::getPostionPages();
        return view('admin.pages.create', compact('pages', 'postion'));
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
            $requestData['slug'] = str_slug($requestData['name']);
        }

        $requestData['slug'] = SysMenu::setTrueSlug($requestData['slug']);

        \DB::transaction(function () use ($request, $requestData){
            if($request->hasFile('avatar')) {
                $requestData['avatar'] = Page::uploadAndResize($request->file('avatar'));
            }
            Page::create($requestData);
        });
        toastr()->success(__('theme::pages.created_success'));

        return redirect('admin/pages');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $postion = Page::getPostionPages();
        $pages = Page::all();
        return view('admin.pages.edit', compact('page', 'pages', 'postion'));
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
        $page = Page::findOrFail($id);

        $requestData = $request->all();
        if (empty($request->get('slug'))){
            $requestData['slug'] = str_slug($requestData['name']);
            $requestData['slug'] = SysMenu::setTrueSlug($requestData['slug']);
        }

        \DB::transaction(function () use ($request, $requestData, $page){
            if($request->hasFile('avatar')) {
                \Storage::delete($page->avatar);
                $requestData['avatar'] = Page::uploadAndResize($request->file('avatar'));
            }
            $page->update($requestData);
        });
        toastr()->success(__('theme::pages.updated_success'));

        return redirect('admin/pages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Page::destroy($id);

        toastr()->success(__('theme::pages.deleted_success'));

        return redirect('admin/pages');
    }
}
