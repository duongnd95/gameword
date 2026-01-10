<?php

namespace App\Http\Controllers\Admin;

use App\AboutUs;
use App\Http\Resources\AboutUsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = config('settings.perpage');

        $aboutus = new AboutUs();
        $active = AboutUs::getListActive();

        if (!empty($keyword)){
            $aboutus = $aboutus->where('title','LIKE',"%$keyword%");
        }

        $aboutus = $aboutus->sortable(['updated_at' => 'desc'])->paginate($perPage);
        return view('admin.aboutus.index', compact('aboutus', 'active'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin.aboutus.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();

        AboutUs::create($requestData);
        toastr()->success(__('theme::aboutus.created_success'));

        return redirect('admin/about-us');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id, Request $request)
    {
        $aboutus = AboutUs::findOrFail($id);

        if ($request->wantsJson()){
            return new AboutUsResource($aboutus);
        }
        if($request->is('api/*')){
            return view('api.content', ['item' => $aboutus]);
        }
        //Lấy đường dẫn cũ
        $backUrl = $request->get('back_url');
        return view('admin.aboutus.show', compact('aboutus', 'backUrl'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id, Request $request)
    {
        $aboutus = AboutUs::findOrFail($id);
        $backUrl = $request->get('back_url');
        return view('admin.aboutus.edit', compact('aboutus', 'backUrl'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $aboutus = AboutUs::findOrFail($id);

        $requestData = $request->all();

        if (empty($requestData['active'])){
            $requestData['active'] = 0;
        }

        $aboutus->update($requestData);
        
        toastr()->success(__('theme::aboutus.updated_success'));

        if ($request->has('back_url')){
            $backUrl = $request->get('back_url');
            if (!empty($backUrl)){
                return redirect($backUrl);
            }
        }

        return redirect('admin/about-us');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        AboutUs::destroy($id);
        toastr()->success( __('theme::news.deleted_success'));

        return redirect('admin/about-us');
    }
}
