<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Newsletter;
use Alert;

class NewsletterController extends Controller
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

        $newsletters = new Newsletter();
        if (!empty($keyword)) {
            $newsletters = Newsletter::where('email', 'LIKE', "%$keyword%");
        }
        $newsletters = $newsletters->sortable('created_at', 'DESC')->paginate($perPage);

        return view('admin.newsletters.index', compact('newsletters'));
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
        $requestData = $request->input('email');
        if (Newsletter::where('email', $requestData)->exists()) {
            Alert::error(__('theme::newsletters.registered_error'),__('theme::newsletters.email_exists'));
        } else {
            Newsletter::create([
                'email' => $requestData
            ]);
            Alert::success(__('theme::newsletters.registered_success'));
        }

        return redirect('/');
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
        Newsletter::destroy($id);
        \Session::flash('flash_message', __('theme::newsletters.deleted_success'));
        return redirect('admin/newsletters');
    }
}
