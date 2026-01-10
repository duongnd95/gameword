<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PromotionResource;
use App\Promotion;

class PromotionController extends Controller
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

        $promotions = new Promotion();
        if (!empty($keyword)){
            $promotions = $promotions->where('title','LIKE',"%$keyword%");
        }
	    if (!empty($request->positive)){
		    $promotions = $promotions->whereIn('positive', $request->positive);
	    }
	    //Lấy các quảng cáo còn ngày khuyến mãi.
	    if (!empty($request->date)){
		    $promotions = $promotions->where([
			    ['date_start', '<=', $request->date],
			    ['date_end', '>=', $request->date],
		    ]);
	    }
        //API: hiển thị ở mobile.
        if($request->wantsJson()){
	        $promotions = $promotions->get();
	        return PromotionResource::collection($promotions);
        }

        $promotions = $promotions->paginate($perPage);
        return view('promotion::promotions.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
