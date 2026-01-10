<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['prefix' => 'bookings', 'middleware' => 'auth'], function () {
	Route::put('ajaxStatus/updateModalStatus', 'AjaxController@updateModalStatus');
	Route::get('ajax/{action}', 'AjaxController@index');
	Route::get('ajax/{module?}/get-report-day-ago', 'AjaxController@getReport30DayAgo');
	Route::resource('customers', 'CustomerController');
    Route::resource('approves', 'ApproveController');
    Route::resource('payments', 'PaymentController');

    Route::get('reports/booking-number/{module?}', 'ReportsController@numberBookingByDate');
    Route::get('reports/booking-number/{module?}/month', 'ReportsController@numberBookingByMonth');
    Route::get('reports/booking-number/{module?}/year', 'ReportsController@numberBookingByYear');

    Route::get('reports/booking-people/{module?}', 'ReportsController@peopleBookingByDate');
    Route::get('reports/booking-people/{module?}/month', 'ReportsController@peopleBookingByMonth');
    Route::get('reports/booking-people/{module?}/year', 'ReportsController@peopleBookingByYear');

    Route::get('reports/finance/{module?}', 'ReportsController@financeBookingByDate');
    Route::get('reports/finance/{module?}/month', 'ReportsController@financeBookingByMonth');
    Route::get('reports/finance/{module?}/year', 'ReportsController@financeBookingByYear');

    Route::get('reports/statistic/{module?}/month', 'ReportsController@bookingStatisticByMonth');
    Route::get('reports/statistic/{module?}/many-month', 'ReportsController@bookingStatisticByManyMonth');
    Route::get('reports/statistic/{module?}/year', 'ReportsController@bookingStatisticByYear');

    Route::get('{module}/export', 'BookingController@export');
    Route::get('{module}/exportExcel', 'BookingController@exportReport');
    Route::post('{module}/import', 'BookingController@import');
    Route::get('{module}/invoice/{id}', 'BookingController@invoice');

	Route::resource('{module}', 'BookingController')->parameters([
		'{module}' => 'id'
	]);
	Route::put('{module}/{id}/cancel', 'BookingController@cancel');



});