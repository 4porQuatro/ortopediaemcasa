<?php

namespace App\Http\Controllers\Store;

use App\Packages\Store\Events\PaymentReceived;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;

class StripeController extends Controller
{
    public function test()
    {
        return view('front.pages.stripe.test');
    }

    public function store()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $order = auth()->user()->orders()->where('id', request()->order)->first();

        $charge = Charge::create([
            'amount'=> $order->total*100,
            'currency'=>'EUR',
            'source'=>request()->input('stripeToken'),
        ]);

        event(new PaymentReceived($order));

        return redirect()->action('Store\StoreController@paymentReceived');

    }
}
