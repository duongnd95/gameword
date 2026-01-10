<?php

namespace App\Http\Controllers;

use App\ModuleInfo;
use Modules\Booking\Entities\Booking;
use App\ChartJs;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Traits\Authorizable;
use App\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            if (\Route::input('module') == 'car' || \Route::input('module') == 'bike'){
                $this->list_services = $this->service['namespaceModel']::pluck('number_plate', 'id');
            }else{
                $this->list_services = $this->service['namespaceModel']::pluck('name', 'id');
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function numberBookingByDate(Request $request)
    {
        $list_services = $this->list_services;
        $service = $this->service;

        $dataBooking = $this->bookingByDate(Booking::REPORT_TYPE['NUMBER'], $request, $service);
        $title = __('reports.number_booking_by_date');

        return view('reports.booking_line', compact('dataBooking','list_services', 'title'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function peopleBookingByDate(Request $request)
    {
        $list_services = $this->list_services;
        $service = $this->service;

        $dataBooking = $this->bookingByDate(Booking::REPORT_TYPE['PEOPLE'], $request, $service);
        $title = __('reports.people_booking_by_date');

        return view('reports.booking_line', compact('dataBooking','list_services', 'title'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function financeBookingByDate(Request $request)
    {
        $list_services = $this->list_services;
        $service = $this->service;

        $dataBooking = $this->bookingByDate(Booking::REPORT_TYPE['FINANCE'], $request, $service);
        $title = __('reports.finance_booking_by_date');

        return view('reports.booking_line', compact('dataBooking','list_services' , 'title'));
    }

    /**
     * @param $reportType = number|people
     * @param Request $request
     *
     * @return array
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
        $bookByDate = Booking::reportBookingByDate( $reportType, $service['table'], $from, $to, $request->get('services_id' ), $request->get('branch_id'), $request->get('agent_id'), $request->get('employee_id'), $request->route('module'));

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
}
