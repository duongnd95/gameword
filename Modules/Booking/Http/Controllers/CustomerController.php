<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Booking\Entities\Booking;
use Modules\Booking\Entities\Customer;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use Session, Alert;

class CustomerController extends Controller
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
        $perPage = Config("settings.perpage");

	    $customers = Customer::sortable(['updated_at' => 'desc']);
        if (!empty($keyword)) {
            $customers = $customers->where('name', 'LIKE', "%$keyword%")
				->orWhere('email', 'LIKE', "%$keyword%")
				->orWhere('phone', 'LIKE', "%$keyword%")
				->orWhere('address', 'LIKE', "%$keyword%")
	            ->orWhere('permanent_address', 'LIKE', "%$keyword%");
        }
	    $customers = $customers->paginate($perPage);

        return view('booking::customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('booking::customers.create');
    }

	/**
	 * Store a newly created resource in storage.
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Illuminate\Validation\ValidationException
	 */
    public function store(Request $request)
    {
        $this->validate($request, [
			'name' => 'required',
			'phone' => 'nullable|numeric|digits_between:7,13|unique:customers',
			'phone_other' => 'nullable',
	        'email' => 'nullable|email|unique:customers',
	        'gender' => 'nullable|in:0,1',
		]);
        $requestData = $request->all();
        
        Customer::create($requestData);


        toastr()->success(__('booking::customers.customer_added'));


        return redirect('bookings/customers');
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
        $customer = Customer::findOrFail($id);

        return view('booking::customers.show', compact('customer'));
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
        $customer = Customer::findOrFail($id);
	    
        return view('booking::customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
	        'name' => 'required',
	        'phone' => 'nullable|numeric|digits_between:7,13|unique:customers,phone,'.$id,
            'phone_other' => 'nullable',
	        'email' => 'nullable|email|unique:customers,email,'.$id,
	        'gender' => 'nullable|in:0,1',
		]);
        $requestData = $request->all();
        
        $customer = Customer::findOrFail($id);
        $customer->update($requestData);

        toastr()->success(__('booking::customers.customer_updated'));


        return redirect('bookings/customers');
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
        $bookings = Booking::pluck('customer_id')->toArray();

        if(in_array($id, $bookings)){
            toastr()->success(__('booking::customers.customer_deleted_fail'));
        }else{
            Customer::destroy($id);
            toastr()->success(__('booking::customers.customer_deleted'));

        }
       
        return redirect('bookings/customers');
    }
}
