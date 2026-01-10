<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Traits\Authorizable;
use App\Http\Controllers\Controller;
use App\ProviderProduct;
use App\SysMenu;
use Session;

class ProviderController extends Controller
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
        $perPage = config('settings.perpage');

        $provider = ProviderProduct::sortable();

        if (!empty($keyword)) {
            $provider = ProviderProduct::where('name', 'LIKE', "%$keyword%");
        }

        $provider = $provider->paginate($perPage);
        return view('admin.providers.index', compact('provider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provider = ProviderProduct::all();
        return view('admin.providers.create', compact('provider'));
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
            'name' => 'required|unique:provider_products,name',
        ]);

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

            ProviderProduct::create($requestData);
        });

        Session::flash('flash_message', __('theme::providers.created_success'));
        return redirect('provider-products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $provider = ProviderProduct::findOrFail($id);
        return view('admin.providers.show', compact('provider'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $provider = ProviderProduct::findOrFail($id);
        $providers = ProviderProduct::all();
     
        return view('admin.providers.edit', compact('provider', 'providers'));
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
        $provider = ProviderProduct::findOrFail($id);

        $requestData = $request->all();
        if (empty($request->get('slug'))) {
            $requestData['slug'] = str_slug($requestData['name']);
            $requestData['slug'] = SysMenu::setTrueSlug($requestData['slug']);
        }
        //Kiểm tra tình trạng
        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }   
        \DB::transaction(function () use ($request, $requestData, $provider) {
            $provider->update($requestData);
        });

        Session::flash('flash_message', __('theme::providers.updated_success'));
        return redirect('provider-products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProviderProduct::destroy($id);

        Session::flash('flash_message', __('theme::providers.deleted_success'));

        return redirect('provider-products');
    }
}
