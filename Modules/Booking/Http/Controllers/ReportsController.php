<?php

namespace Modules\Booking\Http\Controllers;

use App\ChartJs;
use App\ModuleInfo;
use App\Traits\Authorizable;
use App\Utils;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Booking\Entities\Booking;

class ReportsController extends Controller
{
    use Authorizable;

    private $list_services = null;
    private $service = null;
    /**
     * ReportsController constructor
     */
    public function __construct(Request $request)
    {
        if (!empty($request->route('module'))){
            $moduleInfo = new ModuleInfo(\Route::input('module'));
            $this->service = $moduleInfo->getBookingServiceInfo();
        }

    }

    /**
     * Danh sách thống kê số lượng vé theo ngày, tháng, năm
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function numberBookingByDate(Request $request)
    {
        $list_services = $this->list_services;
        $service = $this->service;

        $dataBooking = $this->bookingByDate(Booking::REPORT_TYPE['NUMBER'], $request, $service);

        $dataBookingByDate = 'bookingByDate';

        $title = __('reports.number_booking_by_date');

        return view('booking::reports.booking_line', compact('dataBooking', 'dataBookingByDate', 'list_services', 'title'));
    }

    public function numberBookingByMonth(Request $request){
        $list_services = $this->list_services;
        $service = $this->service;

        $dataBooking = $this->bookingByMonth(Booking::REPORT_TYPE['NUMBER'], $request, $service);
        $dataBookingByMonth = 'bookingByDate';

        $title = __('reports.number_booking_by_date');
        if (isset($request->report_year)){
            $title .= ' '.__('reports.year').' '.$request->report_year;
        }else{
            $title .= ' '.__('reports.year').' '.date('Y');
        }

        return view('booking::reports.booking_line', compact('dataBooking', 'dataBookingByMonth', 'list_services', 'title'));
    }

    public function numberBookingByYear(Request $request){
        $list_services = $this->list_services;
        $service = $this->service;

        $dataBooking = $this->bookingByYear(Booking::REPORT_TYPE['NUMBER'], $request, $service);

        $title = __('reports.number_booking_by_date');
        if (isset($request->report_year)){
            $title .= ' '.__('reports.year').' '.$request->report_year;
        }else{
            $title .= ' '.__('reports.year').' '.date('Y');
        }

        return view('booking::reports.booking_line', compact('dataBooking', 'list_services', 'title'));
    }

    /**
     * Danh sách thống kê số lượng người theo ngày, tháng, năm
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function peopleBookingByDate(Request $request)
    {
        $list_services = $this->list_services;
        $service = $this->service;

        $dataBooking = $this->bookingByDate(Booking::REPORT_TYPE['PEOPLE'], $request, $service);
        $dataBookingByDate = 'bookingByDate';

        $title = __('reports.people_booking_by_date');

        return view('booking::reports.booking_line', compact('dataBooking', 'dataBookingByDate', 'list_services', 'title'));
    }

    public function peopleBookingByMonth(Request $request){
        $list_services = $this->list_services;
        $service = $this->service;

        $dataBooking = $this->bookingByMonth(Booking::REPORT_TYPE['PEOPLE'], $request, $service);
        $dataBookingByMonth = 'bookingByDate';

        $title = __('reports.people_booking_by_date');
        if (isset($request->report_year)){
            $title .= ' '.__('reports.year').' '.$request->report_year;
        }else{
            $title .= ' '.__('reports.year').' '.date('Y');
        }

        return view('booking::reports.booking_line', compact('dataBooking', 'dataBookingByMonth', 'list_services', 'title'));
    }

    public function peopleBookingByYear(Request $request){
        $list_services = $this->list_services;
        $service = $this->service;

        $dataBooking = $this->bookingByYear(Booking::REPORT_TYPE['PEOPLE'], $request, $service);

        $title = __('reports.people_booking_by_date');
        if (isset($request->report_year)){
            $title .= ' '.__('reports.year').' '.$request->report_year;
        }else{
            $title .= ' '.__('reports.year').' '.date('Y');
        }

        return view('booking::reports.booking_line', compact('dataBooking', 'list_services', 'title'));
    }

    /**
     * Danh sách thống kê doanh thu theo ngày, tháng, năm
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function financeBookingByDate(Request $request)
    {
        $list_services = $this->list_services;
        $service = $this->service;

        $dataBooking = $this->bookingByDate(Booking::REPORT_TYPE['FINANCE'], $request, $service);

        $dataBookingByDate = 'bookingByDate';


        $title = __('reports.finance_booking_by_date');

        return view('booking::reports.booking_line', compact('dataBooking', 'dataBookingByDate', 'list_services', 'title'));
    }

    public function financeBookingByMonth(Request $request){
        $list_services = $this->list_services;
        $service = $this->service;

        $dataBooking = $this->bookingByMonth(Booking::REPORT_TYPE['FINANCE'], $request, $service);
        $dataBookingByMonth = 'bookingByDate';

        $title = __('reports.finance_booking_by_date');
        if (isset($request->report_year)){
            $title .= ' '.__('reports.year').' '.$request->report_year;
        }else{
            $title .= ' '.__('reports.year').' '.date('Y');
        }

        return view('booking::reports.booking_line', compact('dataBooking', 'dataBookingByMonth', 'list_services', 'title'));
    }

    public function financeBookingByYear(Request $request){
        $list_services = $this->list_services;
        $service = $this->service;

        $dataBooking = $this->bookingByYear(Booking::REPORT_TYPE['FINANCE'], $request, $service);

        $title = __('reports.finance_booking_by_date');
        if (isset($request->report_year)){
            $title .= ' '.__('reports.year').' '.$request->report_year;
        }else{
            $title .= ' '.__('reports.year').' '.date('Y');
        }

        return view('booking::reports.booking_line', compact('dataBooking', 'list_services', 'title'));
    }


    /**
     * Danh sách thống kê khách hàng đặt vé theo tháng, năm
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function bookingStatisticByMonth(Request $request){
        $this->validate($request,[
            'report_month' => 'nullable',
            'report_year' => 'nullable',
            'services_id' => 'nullable'
        ]);

        //Biểu đồ
        $list_services = $this->list_services;
        $service = $this->service;
        $dataBooking = $this->bookingByDate(Booking::REPORT_TYPE['NUMBER'], $request, $service);

        $fromMonth = $request->get('report_month');
        if (empty($fromMonth)){
            $fromMonth = date('m');
        }
        $fromYear = $request->get('report_year');
        if (empty($fromYear)){
            $fromYear = date('Y');
        }
        $perPage = Config("settings.perpage");

        $bookingData = Booking::byRole()->select(\DB::raw('COUNT(*) as number, customer_id'))->with('customer');

        $bookingData = $bookingData->where('created_at', '>=', $fromYear.'-'.$fromMonth.'-01 00:00:00');
        $bookingData = $bookingData->where('created_at', '<=', $fromYear.'-'.$fromMonth.'-31 23:59:59');

        if (!empty($request->get('services_id'))){
            $module_table = $this->service['table'];
            $service_id = $request->get('services_id');
            $bookingData = $bookingData->whereHas('services', function ($query) use ($module_table, $service_id) {
                $query->withTrashed()->where($module_table.'.id', '=', $service_id);
            });
        }

        $bookingData = $bookingData->groupBy('customer_id')->orderBy('number', 'desc')->paginate($perPage);




        $title = __('reports.statistic_booking_by_date').' '. __('reports.month').' '.$fromMonth.' '.__('reports.year').' '.$fromYear.'';
        return view('booking::reports.report_customer', compact('bookingData', 'title', 'dataBooking', 'list_services'));
    }

    public function bookingStatisticByManyMonth(Request $request){
        $this->validate($request,[
            'report_year' => 'nullable',
            'services_id' => 'nullable'
        ]);
        //Biểu đồ
        $list_services = $this->list_services;
        $service = $this->service;
        $dataBooking = $this->bookingByMonth(Booking::REPORT_TYPE['NUMBER'], $request, $service);

        $fromYear = $request->get('report_year');
        if (empty($fromYear)){
            $fromYear = date('Y');
        }
        if (!empty($request->get('services_id'))){
            $service_id = $request->get('services_id');
        }
        $module_table = $this->service['table'];

        $perPage = Config("settings.perpage");

        //Lấy danh sách các customer có booking
        $bookingData = Booking::byRole()->select(\DB::raw('customer_id, COUNT(*) as number'))->with('customer')
            ->where('created_at', '>=', $fromYear.'-01-01 00:00:00')->where('created_at', '<=', $fromYear.'-12-31 23:59:59');


        if (!empty($request->get('services_id'))) {
            $bookingData = $bookingData->whereHas('services', function ($query) use ($module_table, $service_id) {
                $query->withTrashed()->where($module_table . '.id', '=', $service_id);
            });
        }
        $bookingData = $bookingData->groupBy('customer_id')->orderBy('number', 'desc')->paginate($perPage);

        //Lấy danh sách các booking
        $bookingDataMonth = Booking::byRole()->select(\DB::raw('customer_id, MONTH(created_at) as date, COUNT(*) as number'))->with('customer');

        $bookingDataMonth = $bookingDataMonth->where('created_at', '>=', $fromYear.'-01-01 00:00:00');
        $bookingDataMonth = $bookingDataMonth->where('created_at', '<=', $fromYear.'-12-31 23:59:59');

        if (!empty($request->get('services_id'))){
            $bookingDataMonth = $bookingDataMonth->whereHas('services', function ($query) use ($module_table, $service_id) {
                $query->withTrashed()->where($module_table.'.id', '=', $service_id);
            });
        }

        $bookingDataMonth = $bookingDataMonth->groupBy(['customer_id', \DB::raw('MONTH(created_at)')])->orderBy('number', 'desc')->get();

        $date_month = Utils::getMonth();

        //Gán các booking tương ứng vào năm
        $bookingArr = [];
        foreach ($bookingDataMonth as $item){
            foreach ($date_month as $date_curent){
                if (empty($bookingArr[$item->customer_id][$date_curent])){
                    $bookingArr[$item->customer_id][$date_curent] = 0;
                }
            }
            $bookingArr[$item->customer_id][$item->date] = $item->number;
        }

        $title = __('reports.statistic_booking_by_date').' '.__('reports.year').' '.$fromYear.'';
        return view('booking::reports.report_customer', compact('bookingData', 'date_month', 'bookingArr', 'title', 'dataBooking', 'list_services'));
    }

    public function bookingStatisticByYear(Request $request){
        $this->validate($request,[
            'report_year' => 'nullable',
            'services_id' => 'nullable'
        ]);
        //Biểu đồ
        $list_services = $this->list_services;
        $service = $this->service;
        $dataBooking = $this->bookingByYear(Booking::REPORT_TYPE['NUMBER'], $request, $service);

        $fromYear = $request->get('report_year');
        if (empty($fromYear)){
            $fromYear = date('Y');
        }
        if (!empty($request->get('services_id'))){
            $service_id = $request->get('services_id');
        }
        $module_table = $this->service['table'];

        $perPage = Config("settings.perpage");

        //Lấy danh sách các customer có booking
        $bookingData = Booking::byRole()->select(\DB::raw('customer_id, COUNT(*) as number'))->with('customer')
            ->where('created_at', '>=', '2018-01-01 00:00:00')->where('created_at', '<=', $fromYear.'-12-31 23:59:59');
        if (!empty($request->get('services_id'))){
            $bookingData = $bookingData->whereHas('services', function ($query) use ($module_table, $service_id) {
                $query->withTrashed()->where($module_table.'.id', '=', $service_id);
            });
        }
        $bookingData = $bookingData->groupBy('customer_id')->orderBy('number', 'desc')->paginate($perPage);

        //Lấy danh sách các booking
        $bookingDataYear = Booking::byRole()->select(\DB::raw('customer_id, YEAR(created_at) as date, COUNT(*) as number'))->with('customer');

        $bookingDataYear = $bookingDataYear->where('created_at', '>=', '2018-01-01 00:00:00');
        $bookingDataYear = $bookingDataYear->where('created_at', '<=', $fromYear.'-12-31 23:59:59');

        if (!empty($request->get('services_id'))){
            $bookingDataYear = $bookingDataYear->whereHas('services', function ($query) use ($module_table, $service_id) {
                $query->withTrashed()->where($module_table.'.id', '=', $service_id);
            });
        }
        $bookingDataYear = $bookingDataYear->groupBy(['customer_id', \DB::raw('YEAR(created_at)')])->orderBy('number', 'desc')->get();

        $date_year = Utils::getYear($fromYear);

        //Gán các booking tương ứng vào năm
        $bookingArr = [];
        foreach ($bookingDataYear as $item){
            foreach ($date_year as $date_curent){
                if (empty($bookingArr[$item->customer_id][$date_curent])){
                    $bookingArr[$item->customer_id][$date_curent] = 0;
                }
            }
            $bookingArr[$item->customer_id][$item->date] = $item->number;
        }

        $title = __('reports.statistic_booking_by_date').' '.__('reports.year').' '.$fromYear.'';
        return view('booking::reports.report_customer', compact('bookingData', 'date_year', 'bookingArr', 'title', 'dataBooking', 'list_services'));
    }


    /**
     * Lấy danh sách các booking theo ngày
     *
     * @param $reportType //Loại hình thống kê
     * @param Request $request
     * @param $service
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    private function bookingByDate($reportType , Request $request, $service){
        $this->validate($request,[
            'from' => 'nullable|date|date_format:Y-m-d',
            'to' => 'nullable|date|date_format:Y-m-d',
        ]);

        $from = $request->get('from');
        if(empty($request->get('from'))){
            $date30Ago = date('Y-m-d', strtotime('-29 days'));
            $from = $date30Ago;
        }
        $to = $request->get('to');
        if(empty($request->get('to'))) {
            $today = date( 'Y-m-d' );
            $to = $today ;
        }


        //chart data
        $dates = Utils::generateDateRange(Carbon::parse($from), Carbon::parse($to));

        $dataBooking = [
            'labels' => $dates,
            'datasets' => []
        ];
        //get data
        $dataBooking['datasets'][0] = [
            'label' => __('booking::bookings.services'),
            'borderColor' => ChartJs::COLOR['green'],
            'backgroundColor' => ChartJs::COLOR['green'],
            'fill' => false,
            'data' => []
        ];

        $bookByDate = Booking::reportBookingByDate($reportType, $service['table'], $from, $to, $request->get('services_id' ), $request->get('agent_id'), $request->get('employee_id'), $request->route('module'));

        //prepare data chart
        $datesFormat = [];
        foreach ($dates as $date) {

            $datesFormat[] = Carbon::parse($date)->format(config('settings.format.date'));
            if(isset($dataBooking['datasets'])){
                    $dataBooking['datasets'][0]['data'][] = ! empty( $bookByDate[ $date ] ) ? $bookByDate[ $date ] : 0;
            }
        }

        $dataBooking['labels'] = $datesFormat;

        return $dataBooking;
    }

    /**
     * Lấy danh sách các booking theo tháng
     *
     * @param Request $request
     * @param $service
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     *
     * return $array
     */
    private function bookingByMonth($reportType ,Request $request, $service){
        $this->validate($request,[
            'report_year' => 'nullable',
        ]);

        $fromYear = $request->get('report_year');
        if (empty($fromYear)){
            $fromYear = date('Y');
        }

        //chart data
        $dates = Utils::getMonth();

        $dataBooking = [
            'labels' => $dates,
            'datasets' => []
        ];
        //get data
        $dataBooking['datasets'][0] = [
            'label' => __('booking::bookings.services'),
            'borderColor' => ChartJs::COLOR['green'],
            'backgroundColor' => ChartJs::COLOR['green'],
            'fill' => false,
            'data' => []
        ];

        $bookByMonth = Booking::reportBookingByMonth($reportType, $service['table'], $fromYear, $request->get('services_id' ), $request->get('agent_id'), $request->get('employee_id'), $request->route('module'));

        //prepare data chart
        $datesFormat = [];
        foreach ($dates as $date) {
            $datesFormat[] = 'Tháng '.$date;
            if(isset($dataBooking['datasets'])){
                $dataBooking['datasets'][0]['data'][] = ! empty( $bookByMonth[ $date ] ) ? $bookByMonth[ $date ] : 0;
            }
        }
        $dataBooking['labels'] = $datesFormat;
        return $dataBooking;
    }

    /**
     * Lấy danh sách các booking theo năm
     *
     * @param $reportType //Loại hình thống kê
     * @param Request $request
     * @param $service
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    private function bookingByYear($reportType, Request $request, $service){
        $this->validate($request,[
            'report_year' => 'nullable',
        ]);

        $fromYear = $request->get('report_year');
        if (empty($fromYear)){
            $fromYear = date('Y');
        }

        //chart data
        $dates = Utils::getYear($fromYear);

        $dataBooking = [
            'labels' => $dates,
            'datasets' => []
        ];
        //get data
        $dataBooking['datasets'][0] = [
            'label' => __('booking::bookings.services'),
            'borderColor' => ChartJs::COLOR['green'],
            'backgroundColor' => ChartJs::COLOR['green'],
            'fill' => false,
            'data' => []
        ];

        $bookByMonth = Booking::reportBookingByYear($reportType, $service['table'], $fromYear, $request->get('services_id' ), $request->get('agent_id'), $request->get('employee_id'), $request->route('module'));

        //prepare data chart
        $datesFormat = [];
        foreach ($dates as $date) {
            $datesFormat[] = 'Năm '.$date;
            if(isset($dataBooking['datasets'])){
                $dataBooking['datasets'][0]['data'][] = ! empty( $bookByMonth[ $date ] ) ? $bookByMonth[ $date ] : 0;
            }
        }
        $dataBooking['labels'] = $datesFormat;
        return $dataBooking;
    }


}
