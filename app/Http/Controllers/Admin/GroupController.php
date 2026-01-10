<?php

namespace App\Http\Controllers\Admin;

use App\GroupProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SysMenu;
use Session;

class GroupController extends Controller
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

        $group = GroupProduct::sortable();

        if (!empty($keyword)) {
            $group = GroupProduct::where('name', 'LIKE', "%$keyword%");
        }

        $group = $group->paginate($perPage);
        return view('admin.groups.index', compact('group'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = GroupProduct::all();
        return view('admin.groups.create', compact('group'));
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
        $requestData['slug'] = SysMenu::setTrueSlug($requestData['slug']);

        \DB::transaction(function () use ($request, $requestData) {

            GroupProduct::create($requestData);
        });

        Session::flash('flash_message', __('product::groups.created_success'));
        return redirect('group-products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = GroupProduct::findOrFail($id);
        return view('admin.groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = GroupProduct::findOrFail($id);
        $groups = GroupProduct::all();
    
        return view('admin.groups.edit', compact('group', 'groups'));
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
        $group = GroupProduct::findOrFail($id);

        $requestData = $request->all();
        if (empty($request->get('slug'))) {
            $requestData['slug'] = str_slug($requestData['name']);
            $requestData['slug'] = SysMenu::setTrueSlug($requestData['slug']);
        }
        //Kiểm tra tình trạng
        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }   
        \DB::transaction(function () use ($request, $requestData, $group) {
            $group->update($requestData);
        });

        Session::flash('flash_message', __('theme::groups.updated_success'));
        return redirect('group-products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        GroupProduct::destroy($id);

        Session::flash('flash_message', __('theme::groups.deleted_success'));

        return redirect('group-products');
    }
}
