<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\AttributeProduct;
use Modules\Theme\Entities\Menu;
use Session;

class AttributeController extends Controller
{
    use Authorizable;

    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = config('settings.perpage');

        $attrs = AttributeProduct::sortable();

        if (!empty($keyword)) {
            $attrs = AttributeProduct::where('key', 'LIKE', "%$keyword%");
        }

        $attrs = $attrs->paginate($perPage);
        return view('product::attributes.index', compact('attrs'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        
        return view('product::attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'key' => 'required|unique:product_attributes,key',
            'value' => 'required',
        ]);
        
        $requestData = $request->all();
        $val = $request->value;
        $requestData['value'] = json_encode($val, JSON_FORCE_OBJECT);
        //Kiểm tra tình trạng
        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }
        AttributeProduct::create($requestData);
        Session::flash('flash_message', __('product::attributes.created_success'));
        return redirect('attribute-products');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $attrs = AttributeProduct::findOrFail($id);
        return view('product::attributes.show', compact('attrs'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $attr = AttributeProduct::findOrFail($id);
        
        //Lấy tất cả tên thuộc tính
        $att = json_decode($attr->value, JSON_FORCE_OBJECT);

        return view('product::attributes.edit', compact('attr', 'att'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'key' => 'required',
            'value' => 'required',
        ]);
        $p_attr = AttributeProduct::findOrFail($id);

        $requestData = $request->all();
        $val = $request->value;
        $requestData['value'] = json_encode($val, JSON_FORCE_OBJECT);
        //Kiểm tra tình trạng
        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }   
        $p_attr->update($requestData);
        Session::flash('flash_message', __('product::attributes.updated_success'));
        return redirect('attribute-products');

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        AttributeProduct::destroy($id);

        Session::flash('flash_message', __('product::attributes.deleted_success'));

        return redirect('attribute-products');
    }
}