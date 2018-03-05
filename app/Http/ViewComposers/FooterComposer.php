<?php

namespace App\Http\ViewComposers;

use App\Models\Store\PaymentMethod;
use App\Models\Store\ShippingMethod;
use Illuminate\View\View;

use App\Models\App\Contact;

class FooterComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $contact = Contact::first();

        $payment_methods = PaymentMethod::whereNotNull('images')->where('images', '!=', '[]')->get();
        $shipping_methods = ShippingMethod::whereNotNull('images')->where('images', '!=', '[]')->get();

        $view->with(
            compact(
                'contact',
                'payment_methods',
                'shipping_methods'
            )
        );
    }
}
