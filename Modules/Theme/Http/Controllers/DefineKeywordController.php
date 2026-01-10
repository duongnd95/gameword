<?php

namespace Modules\Theme\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Theme\Entities\DefineKeyword;

class DefineKeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = DefineKeyword::defineKeyword();
        $tabs = array_keys($data);
        return view('theme::back-end.define-keywords.index', compact('tabs','data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('theme::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('theme::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('theme::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $defineConfigs = DefineKeyword::defineKeywordDefault();
        foreach ($defineConfigs as $sc){
            $setting = DefineKeyword::firstOrCreate(
                ['key' => $sc['key']],
                ['value' => $sc['value']]
            );
            $setting->update(['value' => $request->get($sc['key'])]);
        }
        return redirect('admin/define-keywords')->with('flash_message', __('Định nghĩa từ khóa thành công!'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
