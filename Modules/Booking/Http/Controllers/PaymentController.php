<?php

namespace Modules\Booking\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Booking\Entities\PaymentMethod;
use App\Traits\Authorizable;
use Session;

class PaymentController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = config('settings.perpage');

        $payment = PaymentMethod::sortable();

        if (!empty($keyword)) {
            $payment = PaymentMethod::where('name', 'LIKE', "%$keyword%");
        }

        $payment = $payment->paginate($perPage);
        return view('booking::payments.index', compact('payment'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        //Sắp xếp
        $arrange = PaymentMethod::max('arrange');
        $arrange = empty($arrange) ? 1: $arrange+1;

        return view('booking::payments.create', compact( 'arrange'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:credit_banks,name',
        ]);

        $requestData = $request->all();

        //Kiểm tra tình trạng
        if(!isset($request->active)){
            $requestData["active"] = config("settings.inactive");
        }

        \DB::transaction(function () use ($request, $requestData) {
            if ($request->hasFile('image')) {
                $requestData['image'] = PaymentMethod::uploadAndResize($request->file('image'));
            }
            PaymentMethod::create($requestData);
        });

        toastr()->success(__('booking::payments.created_success'));
        //Session::flash('flash_message', __('booking::payments.created_success'));
        return redirect('bookings/payments');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $payment = PaymentMethod::findOrFail($id);
        return view('booking::payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $payment = PaymentMethod::findOrFail($id);
        $payments = PaymentMethod::all();
        //Sắp xếp
        $arrange = PaymentMethod::max('arrange');
        $arrange = empty($arrange) ? 1: $arrange+1;
        return view('booking::payments.edit', compact('payment', 'arrange', 'payments'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $payment = PaymentMethod::findOrFail($id);

        $requestData = $request->all();

        //Kiểm tra tình trạng
        if(!isset($request->active)){
            $requestData["active"] = config("settings.inactive");
        }
        \DB::transaction(function () use ($request, $requestData, $payment) {
            if($request->hasFile('image')) {
                \File::delete($payment->image);
                $requestData['image'] = PaymentMethod::uploadAndResize($request->file('image'));
            }
            $payment->update($requestData);
        });
        toastr()->success(__('booking::payments.updated_success'));
        return redirect('bookings/payments');

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $payment = PaymentMethod::findOrFail($id);
        PaymentMethod::destroy($id);
        \File::delete($payment->image);
        toastr()->success( __('booking::payments.deleted_success'));

        return redirect('bookings/payments');
    }
}
