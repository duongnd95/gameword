<?php

namespace App\Listeners;

use App\Events\PhoneBookingEvent;
use App\Jobs\SendNotificationToDevice;
use App\Notification;
use App\Notifications\PhoneBookingNotify;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PhoneBookingListenerNotification implements ShouldQueue
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
     * @param  PhoneBookingEvent  $event
     * @return void
     */
    public function handle(PhoneBookingEvent $event)
    {
        $phone = $event->phone;
        $userGetNotify = User::whereIn('id', $event->users)->get();

        //save database notification
        \Notification::send($userGetNotify, new PhoneBookingNotify($phone));

        //send notify to device
        $dataNotify = [
            'collapse_key' => '',
            'priority' => "HIGH",//độ ưu tiên: NORMAL, HIGH
            'notification' => [
                'title' => __('notifications.phone_booking_title'),
                'body' => __('notifications.phone_booking', [
                    'phone' => $phone->phone,
                ]),
                'sound' => true
            ],
            'data' => [
                'url' => url('phone-calls')
            ],
        ];
        SendNotificationToDevice::dispatch($userGetNotify, $dataNotify)->delay(now()->subSeconds(5));
    }
}
