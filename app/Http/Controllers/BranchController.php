<?php

namespace App\Http\Controllers;

use App\Branch;
use App\City;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = Config('settings.perpage');
        $branches = Branch::sortable();
        if (!empty($keyword)){
            $branches = $branches->where(function ($query) use ($keyword){
                $query->where('name','LIKE',"%$keyword%")
                    ->orWhere('phone','LIKE',"%$keyword%")
                    ->orWhere('email', 'LIKE', "%$keyword%")
                    ->orWhere('address', 'LIKE', "%$keyword%");
            });
        }
        $branches = $branches->paginate($perPage);
        return view('branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::pluck('name', 'id');
        $cities->prepend(__('message.please_select'), '')->all();
        return view('branches.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BranchRequest $request)
    {
        $request->validated();

        $requestData = $request->all();
        if (!empty($request->birthday)){
            $requestData['birthday'] = Carbon::createFromFormat(config('settings.format.date'),$request->birthday)->format('Y-m-d');
        }
        Branch::create($requestData);
        Session::flash('flash_message', __('branches.created_success'));

        return redirect('branches');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $branch = Branch::findOrFail($id);
        return view('branches.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        $cities = City::pluck('name', 'id');
        $cities->prepend(__('message.please_select'), '')->all();
        return view('branches.edit', compact('branch','cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BranchRequest $request, $id)
    {
        $request->validated();

        $requestData = $request->all();
        if (!empty($request->birthday)){
            $requestData['birthday'] = Carbon::createFromFormat(config('settings.format.date'),$request->birthday)->format('Y-m-d');
        }
        $branch = Branch::findOrFail($id);
        $branch->update($requestData);
        Session::flash('flash_message', __('branches.updated_success'));

        return redirect('branches');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Branch::destroy($id);

        Session::flash('flash_message', __('branches.deleted_success'));

        return redirect('branches');
    }

    public function getProfile(){
        $user = \Auth::user();
        $branch = $user->branch;
        $cities = City::pluck('name', 'id');
        $cities->prepend(__('message.please_select'), '')->all();
        abort_if(!$branch, 404);
        if (!empty($branch->birthday)){
            $branch->birthday = Carbon::parse($branch->birthday)->format(config('settings.format.date'));
        }
        return view('branches.profile', compact('branch', 'cities'));
    }

    public function postProfile(Request $request){
        $user = \Auth::user();
        abort_if(!$user->branch, 404);
        $this->validate($request, [
            'name' => 'required',
            'email' => 'email|nullable',
            'birthday' => 'nullable|date_format:"'.config('settings.format.date').'"',
            'phone' => 'numeric|nullable|digits_between:7,13',
            'address' => 'nullable'
        ]);
        $requestData = $request->all();
        $branch = $user->branch;
        if (!empty($request->birthday)){
            $requestData['birthday'] = Carbon::createFromFormat(config('settings.format.date'),$request->birthday)->format('Y-m-d');
        }
        $branch->update($requestData);
        return Redirect()->back()->with('flash_message',__('branches.updated_success'));
    }
}
