<?php
namespace Modules\Booking\Http\Controllers;

use App\ChartJs;
use App\Http\Controllers\Controller;
use App\ModuleInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Booking\Entities\BookingProperty;
use Modules\Booking\Entities\Booking;
use Modules\Booking\Entities\Approve;
use Modules\Product\Entities\Product;

class AjaxController extends Controller
{
	/**
	 * Gọi ajax: sẽ gọi đến hàm = tên $action
	 * @param Request $action
	 * @param Request $request
	 * @return mixed
	 */
	public function index($action, Request $request)
	{
		return $this->{$action}($request);
	}
	/*
	 * Find customer by phone number
	 */
	public function getCustomerByPhone(Request $request){
		$this->validate($request, [
			'phone' => 'required|numeric'
		]);
		return response()->json(\Modules\Booking\Entities\Customer::where('phone', 'like', "%$request->phone%")->orderBy('updated_at','desc')->limit(10)->get());
	}

	/**
	 * Find customer by email
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function getCustomerByEmail(Request $request){
		$this->validate($request, [
			'email' => 'required'
		]);
		return response()->json(\Modules\Booking\Entities\Customer::where('email', 'like', "%$request->email%")->orderBy('updated_at','desc')->limit(10)->get());
	}

	public function getDataService(Request $request){

        $data_arr = BookingProperty::getDataSelect($request->idServices, $request->moduleName);
	    return response()->json($data_arr);
    }

    public function setConfigProperties(Request $request){
	    $moduleName = $request->moduleName;
        $moduleProperties = new ModuleInfo($moduleName);
        $moduleProperties->configBookingProperties();
        return response()->json($moduleName);
    }

    public function getReport30DayAgo(Request $request){
       $html = ChartJs::dashboardLineCharBooking30DaysAgo();
       return $html;
//       return response()->json($html);
    }
    /**
     * Hien thi modal trang thai
    */
    public function getModalStatus(){
		$approve = Approve::all();
		return response()->json([
			'data' => $approve,
			'success'=>'Get Status Modal successfully.'
		]);
	}
	
	public function updateModalStatus(Request $request){
		$bookings = Booking::findOrFail($request->id);
		$bookings->approve_id = $request->approve_id;
		$bookings->update();
        return response()->json([
			'success'=>'Status change successfully.'
		]);
	}
	/*Get price product*/
    public function getProduct(Request $request){
        $product = Product::with('category')->where('id',$request->id)->first();
        return response()->json($product);
    }
}