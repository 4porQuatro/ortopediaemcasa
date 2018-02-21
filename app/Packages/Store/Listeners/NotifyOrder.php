<?php

namespace App\Packages\Store\Listeners;

use App\Packages\Store\Events\OrderPlaced;
use App\Packages\Store\Mail\OrderPlacedAdminMail;
use App\Packages\Store\Mail\OrderPlacedUserMail;

class NotifyOrder
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
     * @param  OrderPlaced  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        \Mail::queue(new OrderPlacedAdminMail($event->order));
        \Mail::to($event->order->user->email)->queue(new OrderPlacedUserMail($event->order));
    }
}
