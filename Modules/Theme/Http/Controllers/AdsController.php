<?php

namespace Modules\Theme\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Theme\Entities\Ads;
use Session;

class AdsController extends Controller
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

        $ads = Ads::sortable();
        $postion = Ads::getPostionAds();

        if (!empty($keyword)){
            $ads = Ads::where('name','LIKE',"%$keyword%");
        }

        $ads = $ads->paginate($perPage);
        return view('theme::back-end.ads.index', compact('ads', 'postion'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $arrange = Ads::max('arrange');
        $arrange = empty($arrange) ? 1: $arrange+1;
        $postion = Ads::getPostionAds();
        return view('theme::back-end.ads.create', compact('arrange','postion'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required'
        ]);
        $requestData = $request->all();

        Ads::create($requestData);

        Session::flash('flash_message', __('theme::ads.created_success'));
        return redirect('ads');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $ads = Ads::findOrFail($id);
        return view('theme::back-end.ads.show', compact('ads'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $ads = Ads::findOrFail($id);
        $postion = Ads::getPostionAds();
        return view('theme::back-end.ads.edit', compact('ads','postion'));
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
            'image' => 'required'
        ]);
        $ads = Ads::findOrFail($id);

        $requestData = $request->all();
        //Kiểm tra tình trạng
        if(!isset($request->active)){
			$requestData["active"] = config("settings.inactive");
        }
        \DB::transaction(function () use ($request, $requestData, $ads){
            if($request->hasFile('image')) {
				\Storage::delete($ads->avatar);
                $requestData['image'] = Ads::uploadAndResize($request->file('image'));
            }
            $ads->update($requestData);
        });

        Session::flash('flash_message', __('theme::ads.updated_success'));
        return redirect('ads');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Ads::destroy($id);

        Session::flash('flash_message', __('theme::ads.deleted_success'));

        return redirect('ads');
    }
}
