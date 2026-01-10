<?php

namespace App\Listeners;

use App\Events\MailContactEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\MailContactAdmin;
use App\User;


class SendMailContactListener implements ShouldQueue
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
     * @param  MailContactEvent  $event
     * @return void
     */
    public function handle(MailContactEvent $event)
    {
        $contact = $event->contact;
        $admin = User::whereIn('id', $event->admin)->get();
        //send mail active to admin
        foreach ($admin as $item){
            //Kiểm tra email của admin có ko
            if (!empty($item->email)){
                \Mail::to($item->email)->send(new MailContactAdmin($contact));
            }
        }
    }
}
