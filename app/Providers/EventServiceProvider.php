<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\User\NotifyAdmin',
            'App\Listeners\User\NotifyUser',
        ],
        'App\Packages\Store\Events\OrderPlaced' => [
            'App\Packages\Store\Listeners\GeneratePaymentReference',
            'App\Packages\Store\Listeners\NotifyOrder'
        ],
        'App\Packages\Store\Events\PaymentReceived' => [
            'App\Packages\Store\Listeners\NotifyPayment',
            'App\Packages\Store\Listeners\UpdateOrderState'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
