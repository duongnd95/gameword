<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\ProviderProduct;
use Modules\Theme\Entities\Menu;
use Session;

class ProviderController extends Controller
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

        $provider = ProviderProduct::sortable();

        if (!empty($keyword)) {
            $provider = ProviderProduct::where('name', 'LIKE', "%$keyword%");
        }

        $provider = $provider->paginate($perPage);
        return view('product::providers.index', compact('provider'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $provider = ProviderProduct::all();
        return view('product::providers.create', compact('provider'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
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
//        $requestData['slug'] = Menu::setTrueSlug($requestData['slug']);

        \DB::transaction(function () use ($request, $requestData) {

            ProviderProduct::create($requestData);
        });

        toastr()->success(__('product::providers.created_success'));

        return redirect('admin/provider-products');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $provider = ProviderProduct::findOrFail($id);
        return view('product::providers.show', compact('provider'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $provider = ProviderProduct::findOrFail($id);
        $providers = ProviderProduct::all();
     
        return view('product::providers.edit', compact('provider', 'providers'));
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
        $provider = ProviderProduct::findOrFail($id);

        $requestData = $request->all();
        if (empty($request->get('slug'))) {
            $requestData['slug'] = str_slug($requestData['name']);
            $requestData['slug'] = Menu::setTrueSlug($requestData['slug']);
        }
        //Kiểm tra tình trạng
        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }   
        \DB::transaction(function () use ($request, $requestData, $provider) {
            $provider->update($requestData);
        });

        toastr()->success(__('product::providers.updated_success'));

        return redirect('admin/provider-products');

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        ProviderProduct::destroy($id);

        toastr()->success(__('product::providers.deleted_success'));

        return redirect('admin/provider-products');
    }
}