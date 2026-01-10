<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
	Route::get('bookings/{module}/customer-autocomplete', 'BookingController@autocompleteCustomer');
	Route::put('bookings/{module}/{id}/status', 'BookingController@status');
	Route::get('bookings/{module}/{id}/history', 'BookingController@getHistory');
	Route::get('bookings/{module}/export.xlsx', 'BookingController@export');
	Route::resource('bookings/{module}', 'BookingController')->parameters([
		'{module}' => 'id'
	])->only(['index','update', 'destroy']);
	Route::resource('approves', 'ApproveController')->only(['index']);
});
Route::group(['prefix' => 'v1'], function () {//, 'middleware' => 'auth:api'
    Route::resource('bookings/{module}', 'BookingController')->parameters([
        '{module}' => 'id'
    ])->only(['store','show']);

//	Route::get('bookings/booking-tours/export.xlsx', 'BookingToursController@export');
//	Route::get('bookings/booking-journeys/export.xlsx', 'BookingJourneysController@export');

//	Route::get('bookings/booking-journeys/{id}/history', 'BookingJourneysController@getHistory');
//	Route::resource('bookings/booking-journeys', 'BookingJourneysController');
//	Route::put('bookings/booking-journeys/{id}/cancel', 'BookingJourneysController@cancel');

//	Route::get('bookings/booking-tours/{id}/history', 'BookingToursController@getHistory');
//	Route::resource('bookings/booking-tours', 'BookingToursController');
//	Route::put('bookings/booking-tours/{id}/cancel', 'BookingToursController@cancel');
});
