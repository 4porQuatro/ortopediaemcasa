<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Pages\Page;

class StoreController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Shows the thank you page after a user has paid for an order.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function paymentReceived()
    {
        $page = Page::find(19);

        return view(
            'front.pages.store.payment-received',
            compact(
                'page'
            )
        );
    }
}
