<?php

namespace App\Http\Controllers\PrivateArea;

use App\Models\Store\PaymentMethod;
use App\Http\Controllers\Controller;

use App\Models\Pages\Page;
use App\Models\User;

class UserOrdersController extends Controller
{
    /**
     * UserOrdersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Shows the orders history page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $page = Page::find(10);

        $user = User::with('orders.paymentReference')->where('id', auth()->user()->id)->first();

        $payment_methods = PaymentMethod::all();

        return view(
            'pages.user-orders.index',
            compact(
                'page',
                'user',
                'payment_methods'
            )
        );
    }
}
