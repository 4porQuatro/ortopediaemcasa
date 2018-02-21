<?php

namespace App\Listeners\User;

use App\Events\Store\OrderPlaced;
use Illuminate\Auth\Events\Registered;

use App\Mail\Auth\UserRegistrationMail;

class NotifyAdmin
{
    /**
     * Create the event listener.
     *
     * NotifyPayment constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     */
    public function handle(Registered $event)
    {
        \Mail::queue(new UserRegistrationMail($event->user->billing_name, $event->user->email));
    }
}
