<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Storage;
use App\Agent;
use App\Traits\Authorizable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class AgentsController extends Controller
{
	use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        $keyword = $request->get('search');
        //$keyCompany = $request->get('searchCompany');
        $perPage = Config("settings.perpage");

        $agents = Agent::sortable();
	    /*if(\Auth::user()->isAdminCompany()){
		    $agents = $agents->with('company');
		    $companies = Company::all();
		    if (!empty($keyCompany)){
			    $agents = $agents->where('company_id',$keyCompany);
		    }
	    }*/
        if (!empty($keyword)) {
            $agents = $agents->where(function ($query) use ($keyword){
                        $query->where('name', 'LIKE', "%$keyword%")
                            ->orWhere('phone', 'LIKE', "%$keyword%")
                            ->orWhere('email', 'LIKE', "%$keyword%")
                            ->orWhere('address', 'LIKE', "%$keyword%");
                });
        }
        $agents = $agents->paginate($perPage);

        return view('agents.index', compact('agents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
    	/*if(\Auth::user()->isAdminCompany())
            $company = Company::all()->pluck('name','id');*/
        return view('agents.create');
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
			'name' => 'required',
            'logo' => 'image|mimes:jpg,png,jpeg,gif|max:2048',
            'email' => 'email|nullable',
            'birthday' => 'nullable|date_format:"'.config('settings.format.date').'"',
            //'company_id' => \Auth::user()->isAdminCompany() ? 'required' : 'nullable',
            'phone' => 'numeric|nullable|digits_between:7,13'
		]);
        $requestData = $request->all();

        if (!empty($request->birthday)){
            $requestData['birthday'] = Carbon::createFromFormat(config('settings.format.date'),$request->birthday)->format('Y-m-d');
        }
        if ($request->hasFile('logo')){
            $file = $request->file('logo');
            $requestData['logo'] = Agent::saveLogo($file);
        }
        Agent::create($requestData);
        Session::flash('flash_message', __('agents.created_success'));

        return redirect('agents');
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
        $agent = Agent::findOrFail($id);
        return view('agents.show', compact('agent'));
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
        $agent = Agent::findOrFail($id);
	    /*if(\Auth::user()->isAdminCompany())
            $company = Company::all()->pluck('name','id');*/
        if (!empty($agent->birthday)){
            $agent->birthday = Carbon::parse($agent->birthday)->format(config('settings.format.date'));
        }
        return view('agents.edit', compact('agent'));
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
        $this->validate($request, [
            'name' => 'required',
            'logo' => 'image|mimes:jpg,png,jpeg,gif|max:2048',
            'email' => 'email|nullable',
            'birthday' => 'nullable|date_format:"'.config('settings.format.date').'"',
            //'company_id' => \Auth::user()->isAdminCompany() ? 'required' : 'nullable',
            'phone' => 'numeric|nullable|digits_between:7,13'
        ]);
        $requestData = $request->all();
        if (!empty($request->birthday)){
            $requestData['birthday'] = Carbon::createFromFormat(config('settings.format.date'),$request->birthday)->format('Y-m-d');
        }
        $agent = Agent::findOrFail($id);
        $oldFile = $agent->logo;
        if ($request->hasFile('logo')){
            $file = $request->file('logo');
            $requestData['logo'] = Agent::saveLogo($file);
            \Storage::delete($oldFile);
        }
        $agent->update($requestData);
        Session::flash('flash_message', __('agents.updated_success'));

        return redirect('agents');
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
        Agent::destroy($id);

        Session::flash('flash_message', __('agents.deleted_success'));

        return redirect('agents');
    }

    public function getProfile(){
        $user = \Auth::user();
        $agent = $user->agent;
        //$company = $user->company;
	    abort_if(!$agent, 404);
        if (!empty($agent->birthday)){
            $agent->birthday = Carbon::parse($agent->birthday)->format(config('settings.format.date'));
        }
        return view('agents.profile', compact('agent'));
    }

    public function postProfile(Request $request){
        $user = \Auth::user();
	    abort_if(!$user->agent, 404);
        $this->validate($request, [
            'name' => 'required',
            'logo' => 'image|mimes:jpg,png,jpeg,gif|max:2048',
            'email' => 'email|nullable',
            'birthday' => 'nullable|date_format:"'.config('settings.format.date').'"',
            'phone' => 'numeric|nullable|digits_between:7,13'
        ]);
        $requestData = $request->all();
        $agent = $user->agent;
        if (!empty($request->birthday)){
            $requestData['birthday'] = Carbon::createFromFormat(config('settings.format.date'),$request->birthday)->format('Y-m-d');
        }
        $oldFile = $agent->logo;
        if ($request->hasFile('logo')){
            $file = $request->file('logo');
            $requestData['logo'] = Agent::saveLogo($file);
            \Storage::delete($oldFile);
        }
        $agent->update($requestData);
        return Redirect()->back()->with('flash_message',__('agents.updated_success'));
    }
}
