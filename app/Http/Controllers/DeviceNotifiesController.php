<?php

namespace App\Http\Controllers;

use App\DeviceNotify;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Session;

class DeviceNotifiesController extends Controller
{
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
			'token' => 'required',
		]);
		$requestData = $request->only('token');
		$authId = auth()->user()->id;

		if($device = DeviceNotify::where('token', $requestData['token'])->first()){
			if($device->user_id !== $authId) $device->update(['user_id'=>$authId]);
		}else{
			if($request->is("api/*")){
				$requestData['device_type'] = DeviceNotify::$TYPE_MOBILE;
			}else{
				$requestData['device_type'] = DeviceNotify::$TYPE_WEB;
			}
			$requestData['device_info'] = $request->header('User-Agent');
			$requestData['user_id'] = $authId;

			DeviceNotify::create($requestData);
		}

		return response()->json(['success' => 'ok']);
	}
	public function destroy($token)
	{
		DeviceNotify::where('token', $token)->delete();

		return response()->json(['success' => 'ok']);
	}
}
