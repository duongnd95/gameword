<?php

namespace Modules\Theme\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Theme\Entities\Slider;
use Illuminate\Support\Str;
use Menu;
use Session;
use Stringable;

class SliderController extends Controller
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

        $sliders = Slider::sortable();

        if (!empty($keyword)){
            $sliders = Slider::where('name','LIKE',"%$keyword%");
        }

        $sliders = $sliders->paginate($perPage);
        return view('theme::back-end.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $arrange = Slider::max('arrange');
        $arrange = empty($arrange) ? 1: $arrange+1;
        return view('theme::back-end.sliders.create', compact('arrange'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required'
        ]);
        $requestData = $request->all();
        //Kiểm tra tình trạng
        if(!isset($request->active)){
            $requestData["active"] = config("settings.inactive");
        }

        \DB::transaction(function () use ($request, $requestData){
            if($request->hasFile('image')) {
                $requestData['image'] = Slider::uploadAndResize($request->file('image'));
            }
            Slider::create($requestData);
        });

        toastr()->success(__('theme::sliders.created_success'));
        return redirect('admin/sliders');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $slider = Slider::findOrFail($id);
        return view('theme::back-end.sliders.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('theme::back-end.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'image' => 'required'
        ]);
        $slider = Slider::findOrFail($id);

        $requestData = $request->all();
        //Kiểm tra tình trạng
        if(!isset($request->active)){
            $requestData["active"] = config("settings.inactive");
        }
        \DB::transaction(function () use ($request, $requestData, $slider){
            if($request->hasFile('image')) {
                \Storage::delete($slider->avatar);
                $requestData['image'] = Slider::uploadAndResize($request->file('image'));
            }
            $slider->update($requestData);
        });
        toastr()->success(__('theme::sliders.updated_success'));

        return redirect('admin/sliders');

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        Slider::destroy($id);

        toastr()->success(__('theme::sliders.deleted_success'));

        return redirect('admin/sliders');
    }
}
