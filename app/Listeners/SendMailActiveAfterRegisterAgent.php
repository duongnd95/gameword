<?php

namespace App\Listeners;

use App\Events\RegisterEvent;
use App\Mail\MailActiveAgent;
use App\Mail\MailAgentActive;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailActiveAfterRegisterAgent implements ShouldQueue
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
     * @param  object  $event
     * @return void
     */
    public function handle(RegisterEvent $event)
    {
        $agentRegister = $event->agentRegister;
        $admin = User::whereIn('id', $event->admin)->get();
        //send mail active to admin
        foreach ($admin as $item){
            //Kiểm tra email của admin có ko
            if (!empty($item->email)){
                \Mail::to($item->email)->send(new MailActiveAgent($agentRegister));
            }
        }
    }
}
