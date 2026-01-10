<?php

namespace App\Http\Controllers;

use App\Events\PhoneBookingEvent;
use App\PhoneCall;
use Illuminate\Http\Request;

class PhoneController extends Controller
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
        $phones = new PhoneCall();

        if (!empty($keyword)){
            $phones = $phones->where('phone','LIKE',"%$keyword%");
        }

        $phones = $phones->with('user')->orderBy('updated_at', 'desc')->paginate($perPage);
        return view('phone-calls.index', compact('phones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('phone-calls.create');
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
            'phone' => 'numeric|required|digits_between:7,13'
        ]);

        $requestData = $request->all();
        $phone = PhoneCall::create($requestData);

        event(new PhoneBookingEvent($phone));

        if ($request->wantsJson()){
            return response()->json([
                'message' => 'Yêu cầu đặt booking nhanh của bạn đã được gửi'
            ]);
        }
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
        $requestData = $request->all();
        $requestData['user_update_id'] = \Auth::id();

        $phone = PhoneCall::findOrFail($id);
        $phone->update($requestData);

        if($request->expectsJson()){
	        return response()->json([
		        'message' => 'Đã cập nhật trạng thái thành công!'
	        ]);
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
