<?php

namespace App\Http\Controllers\Admin;

use App\Version;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

class VersionController extends Controller
{
    //use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $versions = Version::first();

        return view('admin.versions.index', compact('versions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.versions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'version' => 'required|unique:versions',
        ]);
        $requestData = $request->all();

        Version::create($requestData);

        Session::flash('flash_message', 'Version added!');

        return redirect('admin/versions');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $versions = Version::findOrFail($id);

        return view('admin.versions.show', compact('versions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $versions = Version::findOrFail($id);

        return view('admin.versions.edit', compact('versions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {

        $requestData = $request->all();
        if(empty($requestData['enable_ads']))
            $requestData['enable_ads'] = false;

        $versions = Version::findOrFail($id);
        $versions->update($requestData);

        Session::flash('flash_message', 'Version updated!');

        return redirect('admin/versions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Version::destroy($id);

        Session::flash('flash_message', 'Version deleted!');

        return redirect('admin/versions');
    }
}
