<?php

namespace Modules\Theme\Http\Controllers;

use App\Traits\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Theme\Entities\Partner;

class PartnerController extends Controller
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
        $partners = new Partner();

        if (!empty($keyword)){
            $partners = $partners->where('name','LIKE',"%$keyword%");
        }

        $partners = $partners->orderBy('arrange', 'asc')->paginate($perPage);
        return view('theme::back-end.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $arrange = Partner::max('arrange');
        return view('theme::back-end.partners.create', compact('arrange'));
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
            'image' => 'required',
        ]);
        $requestData = $request->all();
        Partner::create($requestData);
        \Session::flash('flash_message', __('theme::partners.created_success'));
        return redirect('partners');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $partner = Partner::findOrFail($id);
        return view('theme::back-end.partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        return view('theme::back-end.partners.edit', compact('partner'));
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
            'image' => 'required',
        ]);
        $partner = Partner::findOrFail($id);
        $requestData = $request->all();
        if (empty($request->get('active'))){
            $requestData['active'] = 0;
        }
        $partner->update($requestData);
        \Session::flash('flash_message', __('theme::partners.updated_success'));
        return redirect('partners');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Partner::destroy($id);
        \Session::flash('flash_message', __('theme::partners.deleted_success'));
        return redirect('partners');
    }
}
