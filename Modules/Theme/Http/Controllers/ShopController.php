<?php

namespace Modules\Theme\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\Authorizable;
use App\Http\Controllers\Controller;
use Modules\Theme\Entities\Shop;
use Session;

class ShopController extends Controller
{


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = config('settings.perpage');

        $shops = Shop::sortable();

        if (!empty($keyword)){
            $shops = Shop::where('name','LIKE',"%$keyword%");
        }

        $shops = $shops->paginate($perPage);
        return view('theme::back-end.shops.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $arrange = Shop::max('arrange');
        return view('theme::back-end.shops.create', compact('arrange'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $requestData = $request->all();

        \DB::transaction(function () use ($request, $requestData){
            if ($request->hasFile('image')) {
                $requestData['image'] = Shop::uploadAndResize($request->file('image'));
            }
            Shop::create($requestData);
        });


        toastr()->success(__('theme::shops.created_success'));
        return redirect('admin/shops');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $shop = Shop::findOrFail($id);
        return view('theme::back-end.shops.show', compact('shop'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        return view('theme::back-end.shops.edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $shop = Shop::findOrFail($id);
        $requestData = $request->all();
        if (empty($request->get('active'))){
            $requestData['active'] = 0;
        }

        \DB::transaction(function () use ($request, $requestData, $shop){
            if($request->hasFile('image')) {
                \File::delete($shop->image);
                $requestData['image'] = Shop::uploadAndResize($request->file('image'));
            }
            $shop->update($requestData);
        });

        toastr()->success(__('theme::shops.updated_success'));

        return redirect('admin/shops');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $shop = Shop::findOrFail($id);

        \File::delete($shop->image);

        Shop::destroy($id);

        toastr()->success(__('theme::shops.deleted_success'));

        return redirect('admin/shops');
    }
}
