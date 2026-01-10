<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Resources\NotificationResource;
use App\Notification;
use Illuminate\Http\Request;
use Session;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $perPage = Config("settings.perpage");

        $notifications = auth()->user()->notifications()->paginate($perPage);

	    auth()->user()->unreadNotifications->markAsRead();

	    if($request->has('ajax')){
		    return view('notifications.list', compact('notifications'));
	    }
        if($request->wantsJson()){
        	return NotificationResource::collection($notifications);
        }
        return view('notifications.index', compact('notifications'));
    }

    public function numberUnread()
    {
		return  auth()->user()->unreadNotifications->count();
    }
}
