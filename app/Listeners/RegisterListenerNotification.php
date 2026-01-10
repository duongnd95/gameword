<?php

namespace App\Listeners;

use App\Events\RegisterEvent;
use App\Notification;
use App\Notifications\RegisterNotify;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterListenerNotification implements ShouldQueue
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
     * @param  RegisterEvent  $event
     * @return void
     */
    public function handle(RegisterEvent $event)
    {
        $agentRegister = $event->agentRegister;
        $usersReceintNotify = User::whereIn('id', $event->admin)->get();
        //save database notification
        \Notification::send($usersReceintNotify, new RegisterNotify($agentRegister));
    }
}
