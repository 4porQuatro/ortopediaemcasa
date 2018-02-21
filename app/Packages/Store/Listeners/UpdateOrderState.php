<?php

namespace App\Packages\Store\Listeners;

use App\Packages\Store\Events\PaymentReceived;

class UpdateOrderState
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
     * @param PaymentReceived $event
     */
    public function handle(PaymentReceived $event)
    {
        $order = $event->order;
        $order->state_id = 2;
        $order->save();
    }
}
