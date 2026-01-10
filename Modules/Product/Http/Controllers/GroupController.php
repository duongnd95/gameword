<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\GroupProduct;
use Modules\Theme\Entities\Menu;
use Session;

class GroupController extends Controller
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

        $group = GroupProduct::sortable();

        if (!empty($keyword)) {
            $group = GroupProduct::where('name', 'LIKE', "%$keyword%");
        }

        $group = $group->paginate($perPage);
        return view('product::groups.index', compact('group'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $group = GroupProduct::all();
        return view('product::groups.create', compact('group'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:group_products,name',
        ]);

        $requestData = $request->all();
        if (empty($request->get('slug'))) {
            $requestData['slug'] = str_slug($requestData['name']);
        }
        //Kiểm tra tình trạng
        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }   
        $requestData['slug'] = Menu::setTrueSlug($requestData['slug']);

        \DB::transaction(function () use ($request, $requestData) {

            GroupProduct::create($requestData);
        });

        Session::flash('flash_message', __('product::groups.created_success'));
        return redirect('group-products');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $group = GroupProduct::findOrFail($id);
        return view('product::groups.show', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $group = GroupProduct::findOrFail($id);
        $groups = GroupProduct::all();
    
        return view('product::groups.edit', compact('group', 'groups'));
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
        $group = GroupProduct::findOrFail($id);

        $requestData = $request->all();
        if (empty($request->get('slug'))) {
            $requestData['slug'] = str_slug($requestData['name']);
            $requestData['slug'] = Menu::setTrueSlug($requestData['slug']);
        }
        //Kiểm tra tình trạng
        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }   
        \DB::transaction(function () use ($request, $requestData, $group) {
            $group->update($requestData);
        });

        Session::flash('flash_message', __('product::groups.updated_success'));
        return redirect('group-products');

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        GroupProduct::destroy($id);

        Session::flash('flash_message', __('product::groups.deleted_success'));

        return redirect('group-products');
    }
}