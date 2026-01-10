<?php

namespace App\Http\Controllers\Admin;

use App\Notification;
use App\SysMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class NotificationController extends Controller
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

        $notifications = Notification::sortable();

        if (!empty($keyword)){
            $notifications = Notification::where('title','LIKE',"%$keyword%");
        }

        $notifications = $notifications->paginate($perPage);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    
        return view('admin.notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:notifications,title',
        ]);

        $requestData = $request->all();

        \DB::transaction(function () use ($request, $requestData){
            if ($request->hasFile('image')) {
                $requestData['image'] = Notification::uploadAndResize($request->file('image'));
            }
            Notification::create($requestData);
        });

        toastr()->success(__('notifications.created_success'));

        return redirect('admin/notifications');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notifications = Notification::findOrFail($id);
        return view('admin.notifications.show', compact('notifications'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $notifications = Notification::findOrFail($id);
        return view('admin.notifications.edit', compact('notifications'));
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
        $notifications = Notification::findOrFail($id);

        $requestData = $request->all();

        if (empty($requestData['active'])){
            $requestData['active'] = config('settings.inactive');
        }
        \DB::transaction(function () use ($request, $requestData, $notifications){
            if($request->hasFile('image')) {
                \File::delete($notifications->image);
                $requestData['image'] = Notification::uploadAndResize($request->file('avatar'));
            }
            $notifications->update($requestData);
        });

        toastr()->success(__('notifications.updated_success'));
        return redirect('admin/notifications');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        if (!empty($notification->image)){
            \File::delete($notification->image);
        }
        Notification::destroy($id);

        toastr()->success(__('notifications.deleted_success'));

        return redirect('admin/notifications');
    }
}
