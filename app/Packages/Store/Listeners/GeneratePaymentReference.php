<?php

namespace App\Packages\Store\Listeners;

use App\Lib\Store\IfThen;

use App\Models\Store\PaymentReference;
use App\Packages\Store\Events\OrderPlaced;

class GeneratePaymentReference
{
    public $mail;

    /**
     * Create the event listener.
     *
     * GeneratePaymentReference constructor.
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
        $ifthen = new IfThen(
            config('services.ifthen.entity-id'),
            config('services.ifthen.sub-entity-id')
        );

        PaymentReference::create(
            [
                'order_id' => $event->order->id,
                'entity' => config('services.ifthen.entity-id'),
                'reference' => $ifthen->GenerateMbRef($event->order->id, $event->order->total),
                'amount' => $event->order->total
            ]
        );
    }
}
