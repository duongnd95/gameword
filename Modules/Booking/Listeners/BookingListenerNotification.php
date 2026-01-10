<?php

namespace Modules\Booking\Listeners;

use Modules\Booking\Events\BookingEvent;
use App\Jobs\SendNotificationToDevice;
use Modules\Booking\Notifications\BookingNotify;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingListenerNotification implements ShouldQueue
{
	public $tries = 2;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BookingEvent  $event
     * @return void
     */
    public function handle(BookingEvent $event)
    {
	    $booking = $event->booking;
	    $booking->load(['customer', 'services']);
	    $userAction = $event->userAction;
	    $usersReceintNotify = $booking->usersReceiveNotify($userAction);
	    //save database notification
        \Notification::send($usersReceintNotify, new BookingNotify($event->type, $booking, $userAction));
        //send notify to device
        $detail = optional($booking->detail)->bookingable;
	    $dataNotify = [
	    	'collapse_key' => $event->type." ".$booking->id,
		    'priority' => "HIGH",//độ ưu tiên: NORMAL, HIGH
		    'notification' => [
				'title' => __('booking::bookings.notification_'.$event->type),
			    'body' => __('booking::bookings.notification_body_'.$event->type, [
			        'user' => $userAction? $userAction->name : __('frontend.customer'),
				    'item' => $detail ? $detail->name : '',
				    'code' => $booking->code,
				    'phone' => optional($booking->customer)->phone,
				    'name' => optional($booking->customer)->name,
			    ]),
				'sound' => true
		    ],
		    'data' => [
		    	'booking_type' => $detail ? $detail->getTable() : '',
		    	'booking_id' => $booking->id,
			    'url' => url('bookings/bus/'.$booking->id)
		    ],
	    ];
	    SendNotificationToDevice::dispatch($usersReceintNotify, $dataNotify)->delay(now()->subSeconds(5));
    }
}
