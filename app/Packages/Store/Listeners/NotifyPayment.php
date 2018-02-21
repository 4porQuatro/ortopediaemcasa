<?php

namespace App\Packages\Store\Listeners;

use App\Packages\Store\Events\PaymentReceived;
use App\Packages\Store\Mail\PaymentReceivedAdminMail;
use App\Packages\Store\Mail\PaymentReceivedUserMail;

class NotifyPayment
{
    /**
     * NotifyPayment constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PaymentReceived  $event
     * @return void
     */
    public function handle(PaymentReceived $event)
    {
        \Mail::queue(new PaymentReceivedAdminMail($event->order));
        \Mail::to($event->order->user->email)->queue(new PaymentReceivedUserMail($event->order));
    }
}
