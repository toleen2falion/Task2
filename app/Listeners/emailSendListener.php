<?php

namespace App\Listeners;

use App\Events\emailSendEvent;
use App\Notifications\RegisterNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class emailSendListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(emailSendEvent $event): void
    {
        //
          
        $event->user->notify(new RegisterNotification());
    }
}
