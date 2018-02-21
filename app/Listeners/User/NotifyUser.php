<?php

namespace App\Listeners\User;

use App\Events\Store\OrderPlaced;
use Illuminate\Auth\Events\Registered;

use App\Mail\Auth\WelcomeUserMail;

class NotifyUser
{
    /**
     * Create the event listener.
     *
     * NotifyUser constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderPlaced  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        \Mail::to($event->user->email, $event->user->billing_name)->send(new WelcomeUserMail($event->user->billing_name));
    }
}
