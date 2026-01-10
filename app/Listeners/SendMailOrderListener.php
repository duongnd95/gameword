<?php

namespace App\Listeners;

use App\Events\MailOrderEvent;
use App\Setting;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Modules\Booking\Entities\Booking;
use App\Mail\MailOrderCustomer;
use App\Mail\MailOrderAdmin;


class SendMailOrderListener implements ShouldQueue
{
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
     * @param  MailOrderEvent  $event
     * @return void
     */
    public function handle(MailOrderEvent $event)
    {
        $settings = Setting::allConfigsKeyValue();
        $booking = Booking::where('id', $event->booking)->first();
        $mailCustomer = $booking->customer->email;
        if (!empty($mailCustomer)){
            if (!empty($settings['company_email_order'])){
                \Mail::to($mailCustomer)->send(new MailOrderCustomer($booking, $event->order, $settings));
                \Mail::to($settings['company_email_order'])->send(new MailOrderAdmin($booking, $event->order, $settings));
            }else{
                \Mail::to($mailCustomer)->send(new MailOrderCustomer($booking, $event->order, $settings));
                \Mail::to($settings['company_email'])->send(new MailOrderAdmin($booking, $event->order, $settings));
            }
        }
    }
}
