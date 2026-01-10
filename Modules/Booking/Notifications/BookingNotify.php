<?php

namespace Modules\Booking\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingNotify extends Notification implements ShouldQueue
{
    use Queueable;

    private $booking;
    private $type;
    private $userAction;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type ,$booking, $userAction = null)
    {
	    $this->type = $type;
        $this->booking = $booking;
        $this->userAction = $userAction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
    	if($this->userAction) $this->userAction->loadMissing('profile');
	    $detail = optional($this->booking->detail)->bookingable;
        return [
        	'type' => $this->type,
	        'booking_type' => $detail ? $detail->getTable() : '',
        	'user' => $this->userAction,
			'booking' => $this->booking->toArray()
        ];
    }
}
