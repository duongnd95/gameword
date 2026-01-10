<?php

namespace Modules\Booking\Http\Controllers;

use App\Events\LogEvent;
use App\Http\Resources\LogActivityResource;
use App\ModuleInfo;
use App\PhoneCall;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Booking\Entities\Approve;
use Modules\Booking\Entities\Booking;
use Modules\Booking\Entities\BookingItem;
use Modules\Booking\Entities\BookingProperty;
use Modules\Booking\Exports\ReportsExport;
use Modules\Bus\Entities\CarType;
use Modules\Booking\Entities\Customer;
use Modules\Booking\Events\BookingEvent;
use Modules\Booking\Exports\BookingsExport;
use Modules\Booking\Imports\BookingsImport;
use Modules\Booking\Transformers\BookingResource;
use Modules\Booking\Transformers\CustomerResource;
use Modules\Product\Entities\Product;
use Session;

class BookingController extends Controller
{
    private $module;
    private $service;
    private $moduleName;

    /**
     * BookingController constructor.
     */
    public function __construct(Request $request)
    {
        //***Check module***
        if ($request->bearerToken()) {
            $this->middleware('auth:api');
        }

        //1.get module
        $moduleInfo = new ModuleInfo(\Route::input('module'));

        //4.Initial params
        $this->module = $moduleInfo->getModule();
        $this->service = $moduleInfo->getBookingServiceInfo();
        $this->moduleName = $moduleInfo->getModuleName();
        \View::share('serviceInfo', $this->service);
    }

    /**
     * Check and save customer info
     * @param $custom_id
     * @param $customerInfo
     *
     * @return Customer
     */
    private function saveCustomer($custom_id, $customerInfo)
    {
        //If exits customer_id: Đã có id khách hàng
        if ($custom_id && $customer = Customer::find($custom_id)) {
            $customer->update($customerInfo);
        } else {
            //Check if customer is empty (kiểm tra xem dữ liệu KH đã có hay ko có nhập)
            $isCustomerEmpty = true;
            foreach ($customerInfo as $key => $value) {
                if ((is_array($value) && !empty($value)) || (is_string($value) && trim($value) !== '')) {
                    $isCustomerEmpty = false;
                    break;
                }
            }
            //If customer is not empty: save data
            if (!$isCustomerEmpty) {
                //Find customer by phone or email: kiểm tra xem có KH trùng với số phone hoặc email không
                if (!empty(trim($customerInfo['phone'])) || !empty(trim($customerInfo['email']))) {
                    $customer = new Customer();
                    if (!empty(trim($customerInfo['phone']))) $customer = $customer->where('phone', trim($customerInfo['phone']));
                    if (!empty($customerInfo['email'])) {
                        if (!empty(trim($customerInfo['email']))) $customer = $customer->orWhere('email', trim($customerInfo['email']));
                    }

                    if ($customer = $customer->first()) {
                        //Nếu có KH trung email hoặc phone thì cập nhật lại thông tin KH - xóa đi các trường rỗng trc khi cập nhật
                        //remove empty data and updated customer
                        $customer->update(array_where($customerInfo, function ($value, $key) {
                            return (is_array($value) && !empty($value)) || (is_string($value) && trim($value) !== '');
                        }));
                    } else {
                        $customer = Customer::create($customerInfo);
                    }
                } else {
                    $customer = Customer::create($customerInfo);
                }
            }
        }
        return $customer;
    }

    public function autocompleteCustomer(Request $request)
    {
        $this->validate($request, [
            'name' => 'required_without_all:phone',
            'phone' => 'required_without_all:name|max:20'
        ]);
        $where = [];
        if (!empty($request->get('name'))) {
            $where[] = ['name', 'LIKE', '%' . $request->get('name') . '%'];
        }
        if (!empty($request->get('phone'))) {
            $where[] = ['phone', 'LIKE', '%' . $request->get('phone') . '%'];
        }
        if (!empty($request->get('email'))) {
            $where[] = ['email', 'LIKE', '%' . $request->get('email') . '%'];
        }
        if (count($where) > 0) {
            $customers = Customer::where($where)->paginate(Config("settings.perpage"));
            return CustomerResource::collection($customers);
        }
        return response()->json([]);
    }

    /**
     * Display a listing of the resource.
     * @param $module
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index($module, Request $request)
    {

        //$dateValidate = $request->wantsJson() ? 'date|date_format:Y-m-d' : 'date_format:"'.config('settings.format.date').'"';
        $this->validate($request, [
            'search' => 'nullable',
            'service_id' => 'nullable|integer',//tour or journey id
            'approve_id' => 'nullable'
        ]);

        $keyword = $request->get('search');
        $perPage = Config("settings.perpage");

        $bookings = Booking::byRole();

        if (!empty($keyword)) {
            $bookings = $bookings->whereHas('customer', function ($query) use ($keyword) {
                $query->where('name', 'like', "%$keyword%")
                    ->orWhere('phone', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
                    ->orWhere('code', 'like', "%$keyword%");
            });
        }

        //booking created by me
        if ($request->has('me')) {
            $bookings = $bookings->where('creator_id', \Auth::id());
        }

        if (!empty($request->get('approve_id'))) {
            $bookings = $bookings->where('approve_id', $request->get('approve_id'));
        }

        if(!empty($request->get('service_id'))){
            $module_table = $this->service['table'];
            $service_id = $request->get('service_id');
            $bookings = $bookings->whereHas('services', function ($query) use ($module_table, $service_id) {
                $query->withTrashed()->where($module_table.'.id', '=', $service_id);
            });
        }
        $bookings = $bookings->sortable(['created_at' => 'asc'])->paginate($perPage);

        $bookings->load(['creator' => function ($query) {
            $query->select('id', 'username', 'name');
        }, 'customer', 'services', 'approve', 'properties']);

        $status = Approve::pluck('name', 'id')->toArray();

        return view('booking::bookings.index', compact('bookings', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     * @param $module
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($module, Request $request)
    {
        $phone = null;
        if (isset($request->phone_id)) {
            $phone = PhoneCall::findOrFail($request->phone_id);
        }

        $booking = new Booking();

        $services = $this->service['namespaceModel']::get();
        $services = $services->mapWithKeys(function ($item) {
            return [$item->id => $item->{$this->service['column_name']}];
        });
        $services->all();
        if (!$this->service['multiple']) $services->prepend("--" . $this->service['label'] . "--", '')->all();

        $approves = Approve::pluck('name', 'id');

        return view('booking::bookings.create', compact('booking', 'services', 'approves', 'phone'));
    }

    /**
     * Store a newly created resource in storage.
     * @param $module
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function store($module, Request $request)
    {

        $validate = [
            'customer.id' => 'nullable|integer',
            'customer.phone' => 'required|numeric|digits_between:7,13',
            'customer.name' => 'required',
            'customer.email' => 'nullable|email',
            'customer.gender' => 'nullable|in:0,1',
        ];
        $this->validate($request, $validate);
        $requestData = $request->all();
        $booking = new Booking();

        \DB::transaction(function () use ($requestData, &$booking, $request) {
            //Kiểm tra trạng thái, mặc định lấy trạng thái đầu tiên
            if (!isset($requestData['approve_id'])) {
                $requestData['approve_id'] = Approve::orderBy('number')->first()->value('id');
            }
            //Customer
            $customerInfo = $requestData['customer'];
            $customer = $this->saveCustomer(!empty($customerInfo['id']) ? $customerInfo['id'] : null, $customerInfo);
            if (!empty($customer)) $requestData['customer_id'] = $customer->id;
            //Save booking
            $booking = Booking::create($requestData);
            //$booking->services()->attach($booking->id, ['price' => $requestData['total_price']]);
           // $total_quantity = 0;

            foreach ($requestData['product'] as $key => $val){
                $booking->bookingDetail()->attach($key, ['price' => $requestData['total_price'], 'quantity' => $val['quantity']]);
                //$total_quantity += $val['quantity'];
            }

        });

        Session::flash('flash_message', __('booking::bookings.created'));

        return redirect("bookings/$module");
    }

    /**
     * Show the specified resource.
     * @param $module
     * @param $id
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|BookingResource
     */
    public function show($module, $id, Request $request)
    {
        $booking = Booking::byRole()->with(['services', 'properties', 'approve'])->findOrFail($id);
        if ($request->wantsJson()) {
            return new BookingResource($booking);
        }

        //Lấy đường dẫn cũ
        $backUrl = $request->get('back_url');

        return view('booking::bookings.show', compact('booking', 'module', 'backUrl'));
    }

    /**
     * Lịch sử chỉnh sửa booking
     * @param $id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getHistory($module, $id)
    {
        $booking = Booking::byRole()->findOrFail($id);
        return LogActivityResource::collection($booking->history);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($module, $id, Request $request)
    {
        $booking = Booking::byRole()->with(['services', 'customer'])->findOrFail($id);

        //Dùng id của dịch vụ lấy ra mảng các dịch vụ để thể hiện dữ liệu select khi EDIT
        //$list_services = $booking->services->first();
        //$select_arr = BookingProperty::getDataSelect($list_services->id, $this->moduleName);

        $services = $this->service['namespaceModel']::get();
        $services = $services->mapWithKeys(function ($item) {
            return [$item->id => $item->{$this->service['column_name']}];
        });
        $services->all();

        if (!$this->service['multiple']) $services->prepend("--" . $this->service['label'] . "--", '')->all();

        $approves = Approve::pluck('name', 'id');

        //Lấy đường dẫn cũ
        $backUrl = $request->get('back_url');
        return view('booking::bookings.edit', compact('booking', 'services', 'approves', 'backUrl'));
    }

    /**
     * Update the specified resource in storage.
     * @param $module
     * @param $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function update($module, $id, Request $request)
    {
        $validate = [
            'customer.id' => 'nullable|integer',
            'customer.phone' => 'required|numeric|digits_between:7,13',
            'customer.name' => 'required',
            'customer.email' => 'nullable|email',
            'customer.gender' => 'nullable|in:0,1',
        ];
        $this->validate($request, $validate);

        $requestData = $request->all();

        $booking = Booking::byRole()->with(['services'])->findOrFail($id);
        \DB::transaction(function () use ($requestData, &$booking, $request) {

            //Kiểm tra trạng thái, mặc định trạng thái đầu tiên
            if (!isset($requestData['approve_id'])) {
                $requestData['approve_id'] = Approve::orderBy('number')->first()->value('id');
            }

            //Customer
            $customerInfo = $requestData['customer'];
            $customer = $this->saveCustomer($booking->customer_id, $customerInfo);
            if (!empty($customer)) $requestData['customer_id'] = $customer->id;
            //Save booking
            $booking->update($requestData);
            $booking->bookingDetail()->detach();
            foreach ($requestData['product'] as $key => $val){
                $booking->bookingDetail()->attach($key, ['price' => $val['price'], 'quantity' => $val['quantity']]);
                //$booking->bookingItem()->sync([$key => ['quantity' => $val['quantity']]], false);
            }

        });

        Session::flash('flash_message', __('booking::bookings.updated'));
        //toastr()->success(__('booking::bookings.updated'));

        if ($request->has('back_url')) {
            $backUrl = $request->get('back_url');
            if (!empty($backUrl)) {
                return redirect($backUrl);
            }
        }

        return redirect("bookings/$module");
    }

    /**
     * Cập nhật trạng thái booking
     * @param $module
     * @param $id
     * @param Request $request
     */
    public function status($module, $id, Request $request)
    {
        //validate
        $this->validate($request, [
            'approve_id' => 'required|exists:approves,id'
        ]);
        $requestData = $request->only('approve_id');

        //save
        $booking = Booking::findOrFail($id);
        $booking->update($requestData);
        event(new BookingEvent(config('settings.notification_type.booking_updated'), $booking));
        event(new LogEvent('updated', $booking, $request));

        //return
        if ($request->wantsJson()) {
            return response()->json([
                'message' => __('booking::bookings.status_updated'),
                'Booking_id' => $booking->id
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param $module
     * @param $id
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($module, $id, Request $request)
    {
        $booking = Booking::byRole()->findOrFail($id);//byRole('journeys')->
        $booking->delete();

        event(new LogEvent('deleted', $booking, $request));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => __('booking::bookings.deleted'),
                'id' => $booking->id
            ]);
        }

        Session::flash('flash_message', __('booking::bookings.deleted'));

        return redirect('bookings/' . $module);
    }

    public function export(Request $request)
    {
        $dateValidate = $request->wantsJson() ? 'date|date_format:Y-m-d' : 'date_format:"' . config('settings.format.date') . '"';
        $this->validate($request, [
            'departure_date' => 'nullable|' . $dateValidate,
            'from' => 'nullable|date|date_format:Y-m-d',
            'to' => 'nullable|date|date_format:Y-m-d',
            'search' => 'nullable',
            'service_id' => 'nullable|integer',//tour or journey id
            'approve_id' => 'nullable'
        ]);

        $bookings = new Booking();
        $bookings = $bookings->byRole();
        $keyword = $request->get('search');

        if (!empty($keyword)) {
            $bookings = $bookings->whereHas('customer', function ($query) use ($keyword) {
                $query->where('name', 'like', "%$keyword%")
                    ->orWhere('phone', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            });
        }
        //booking created by me
        if ($request->has('me')) {
            $bookings = $bookings->where('creator_id', \Auth::id());
        }
        //departure_date
        if (!empty($request->get('departure_date'))) {
            $bookingPropertyId = BookingProperty::where([['key', '=', 'departure_date'], ['module', '=', $this->moduleName]])->value('id');
            if ($request->wantsJson()) {
                $bookings = $bookings->whereHas('properties', function ($query) use ($request, $bookingPropertyId) {
                    $query->where('booking_property_id', $bookingPropertyId)
                        ->where('value', $request->get('departure_date'));
                });
            } else {
                $bookings = $bookings->whereHas('properties', function ($query) use ($request, $bookingPropertyId) {
                    $query->where('booking_property_id', $bookingPropertyId)
                        ->where('value', Carbon::createFromFormat(config('settings.format.date'), $request->get('departure_date'))->format('Y-m-d'));
                });
            }
        }
        if (!empty($request->get('approved'))) {
            $bookings = $bookings->where('approve_id', $request->get('approve_id'));
        }
        //date created
        if (!empty($request->get('from'))) {
            $bookings = $bookings->where('created_at', '>=', $from = $request->get('from') . " 00:00:00");
        }
        if (!empty($request->get('to'))) {
            $bookings = $bookings->where('created_at', '<=', $request->get('to') . " 23:59:59");
        }
        if (!empty($request->get('service_id'))) {
            $module_table = $this->service['table'];
            $service_id = $request->get('service_id');
            $bookings = $bookings->whereHas('services', function ($query) use ($module_table, $service_id) {
                $query->where($module_table . '.id', '=', $service_id);
            });
        }

        $bookings = $bookings->sortable(['updated_at' => 'desc'])->with(['creator:id,username,name', 'customer', 'detail', 'detail.bookingable' => function ($query) {
            $query;
        }, 'properties', 'approve']);

        return \Maatwebsite\Excel\Facades\Excel::download(new BookingsExport(\Route::input('module'), $bookings), \Route::input('module') . Carbon::now() . '.xlsx');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('file');
        $destinationPath = 'uploads/imports';
        $file->move($destinationPath, $file->getClientOriginalName());
        $path = base_path() . '/public/' . $destinationPath . '/' . $file->getClientOriginalName();
        $collection = (new BookingsImport())->toCollection($path);
        $importBookings = [];
        $rowNameProduct = [];

        if ($collection != null && count($collection) > 0) {
            $rows = $collection[0];
            if ($rows != null && count($rows) > 0) {
                for ($i = 1; $i < count($rows); $i++) {
                    $rowDataExcel = $rows[$i];
//                    if($this->IsNullOrEmptyString($rowDataExcel[1])){
//                        $product = Product::create([
//                            'name' => $rowDataExcel[7],
//                            'price' =>  $rowDataExcel[8],
//                        ]);
//
//                    }
                    if (!$this->IsNullOrEmptyString($rowDataExcel[1])) {
                        $rowData = $this->fetchRowData($rowDataExcel[1], $rowDataExcel[2], $rowDataExcel[3], $rowDataExcel[4], $rowDataExcel[5], $rowDataExcel[6],
                            $rowDataExcel[7], $rowDataExcel[8], $rowDataExcel[9]);
                        $rowNameProduct[] = $rowDataExcel[8];
                        $importBookings[] = $rowData;
                    }
                }
            }
        }

        $isSuccess = false;

        $productName = Product::pluck('name')->toArray();

        if (array_diff($rowNameProduct, $productName) != null) {
            toast('Sản phẩm bạn nhập không tồn tại!','error');
            return redirect()->back();
        } else {
            foreach ($importBookings as $item) {
                if ($item['is_error'] == 0) //0: nghia la khong loi, 1: la bi loi
                {
                    $this->checkAndSaveData($item['name'],
                        $item['phone'], $item['email'], $item['address'], $item['total_price'],
                        $item['note'], $item['approve_id'], $item['name_p'], $item['quantity']);
                    $isSuccess = true;
                }
            }
            if ($isSuccess) {
                toast('Thêm bản ghi thành công!','success');
                return redirect('bookings/product');
            }
        }

    }


    public function fetchRowData($name, $phone, $email, $address, $total_price, $note, $approve_id, $name_p, $qty_p)
    {
        $message = "";
        $isError = 0;
        if ($this->IsNullOrEmptyString($name)) {
            $message = 'Họ và tên bắt buộc phải nhập';
            $isError = 1;
        } else if ($this->IsNullOrEmptyString($phone)) {
            $message = 'Điện thoại bắt buộc phải nhập';
            $isError = 1;
        } else if ($this->IsNullOrEmptyString($email)) {
            $message = 'Email bắt buộc phải nhập';
            $isError = 1;
        } else if ($this->IsNullOrEmptyString($address)) {
            $message = 'Địa chỉ bắt buộc phải nhập';
            $isError = 1;
        } else if ($this->IsNullOrEmptyString($total_price)) {
            $message = 'Tổng tiền bắt buộc phải nhập';
            $isError = 1;
        } else if ($this->IsNullOrEmptyString($note)) {
            $message = 'Lưu ý bắt buộc phải nhập';
            $isError = 1;
        }


        return [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'total_price' => $total_price,
            'note' => $note,
            'approve_id' => $approve_id,
            'name_p' => $name_p,
            'quantity' => $qty_p,
            'message' => $message,
            'is_error' => $isError
        ];
    }

    private function IsNullOrEmptyString($str)
    {
        return (!isset($str) || trim($str) === '');
    }

    public function checkAndSaveData($name, $phone, $email, $address, $total_price, $note, $approve_id, $name_p, $qty_p)
    {
        if ($this->IsNullOrEmptyString($name) ||
            $this->IsNullOrEmptyString($total_price) || $this->IsNullOrEmptyString($phone) || $this->IsNullOrEmptyString($email) || $this->IsNullOrEmptyString($note)) {
            return;
        }

        $this->saveData($name, $phone, $email, $address, $total_price, $note, $approve_id, $name_p, $qty_p);

    }

    public function saveData($name, $phone, $email, $address, $total_price, $note, $approve_id, $name_p, $qty_p)
    {

        $approve = Approve::where('name', $approve_id)->pluck('id')->toArray();

        $idProduct = Product::where('name', $name_p)->pluck('id')->toArray();

        $customer = Customer::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,

        ]);
        foreach ($approve as $key ) {
            $booking = Booking::create([
                'customer_id' => $customer->id,
                'total_price' => $total_price,
                'note' => $note,
                'approve_id' => $key,
            ]);
        }
        $booking->services()->attach($booking->id, ['price' => $total_price]);

        foreach ($idProduct as $key ) {
            $booking->bookingItem()->attach($key, ['quantity' => $qty_p]);
        }
//        //Kiểm tra trạng thái, mặc định lấy trạng thái đầu tiên
//        if (!isset($requestData['approve_id'])) {
//            $requestData['approve_id'] = Approve::orderBy('number')->first()->value('id');
//        }


    }

    //Export report excel
    public function exportReport()
    {
        $reports = Booking::all();
        return \Maatwebsite\Excel\Facades\Excel::download(new ReportsExport, 'reports-' . Carbon::now() . '.xlsx');
    }

    //Invoice
    public function invoice($module, $id, Request $request)
    {
        $booking = Booking::byRole()->with(['services', 'properties', 'approve'])->findOrFail($id);
        $module_properties = BookingProperty::where('module', $this->moduleName)->get();

        $productItems = BookingItem::where('booking_id', $booking->id)->get();

        $products = [];
        foreach ($productItems as $item) {
            $products[] = [Product::where('id', $item->product_id)->first(), $item->quantity];
        }

        if ($request->wantsJson()) {
            return new BookingResource($booking);
        }


        return view('booking::bookings.invoice', compact('booking', 'module', 'module_properties', 'products'));
    }
}
