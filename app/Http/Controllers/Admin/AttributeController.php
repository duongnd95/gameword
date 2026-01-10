<?php

namespace App\Http\Controllers\Admin;

use App\AttributeProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Authorizable;
use Session;

class AttributeController extends Controller
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

        $attrs = AttributeProduct::sortable();

        if (!empty($keyword)) {
            $attrs = AttributeProduct::where('key', 'LIKE', "%$keyword%");
        }

        $attrs = $attrs->paginate($perPage);
        return view('admin.attributes.index', compact('attrs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.attributes.create');
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
        $val = $request->value;
        $requestData['value'] = json_encode($val, JSON_FORCE_OBJECT);
        //Kiểm tra tình trạng
        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }
        AttributeProduct::create($requestData);
        Session::flash('flash_message', __('theme::attributes.created_success'));
        return redirect('attribute-products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attrs = AttributeProduct::findOrFail($id);
        return view('admin.attributes.show', compact('attrs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attr = AttributeProduct::findOrFail($id);
        
        //Lấy tất cả tên thuộc tính
        $att = json_decode($attr->value, JSON_FORCE_OBJECT);

        return view('admin.attributes.edit', compact('attr', 'att'));
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
        $p_attr = AttributeProduct::findOrFail($id);

        $requestData = $request->all();
        $val = $request->value;
        $requestData['value'] = json_encode($val, JSON_FORCE_OBJECT);
        //Kiểm tra tình trạng
        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }   
        $p_attr->update($requestData);
        Session::flash('flash_message', __('theme::attributes.updated_success'));
        return redirect('attribute-products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AttributeProduct::destroy($id);

        Session::flash('flash_message', __('theme::attributes.deleted_success'));

        return redirect('attribute-products');
    }
}
