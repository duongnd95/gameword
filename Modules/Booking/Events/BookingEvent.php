<?php

namespace Modules\Booking\Events;

use Illuminate\Queue\SerializesModels;

class BookingEvent
{
    use SerializesModels;
	public $booking, $type, $userAction;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $booking)
    {
        $this->type = $type;
        $this->booking = $booking;
	    $this->userAction = \Auth::check() == true ? auth()->user() : null;
    }
}
