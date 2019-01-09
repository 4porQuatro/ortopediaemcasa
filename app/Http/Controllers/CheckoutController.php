<?php

namespace App\Http\Controllers;

use App\Models\Store\Order;
use Illuminate\Http\Request;

use App\Lib\Store\Cart;
use App\Lib\Store\Price;

use App\Models\Pages\Page;
use App\Models\Store\PaymentMethod;

use App\Repositories\ShippingRepository;

class CheckoutController extends Controller
{
    private $shipping_repo;

    /**
     * CheckoutController constructor.
     *
     * @param ShippingRepository $shipping_repo
     */
    public function __construct(ShippingRepository $shipping_repo){
        $this->shipping_repo = $shipping_repo;
        $this->middleware('auth')->only('conclude');
    }

    /**
     * Shows the checkout page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $page = Page::find(16);

        $user = auth()->user();

        // selected voucher
        $voucher_cart = Cart::instance('voucher');
        $voucher_items = $voucher_cart->content();

        $voucher_code = ($voucher_items->count()) ? current(current($voucher_items))->name : null;

        // points spent
        $points_spent = (Cart::instance('points')->content()->count()) ? Cart::instance('points')->content()->first()->qty : 0;

        // cart items
        $cart_items = Cart::instance('items')->content();


        return view(
            'front.pages.cart.index',
            compact(
                'page',
                'user',
                'voucher_code',
                'points_spent',
                'cart_items'
            )
        );
    }

    /**
     * Renders the cart items partial.
     *
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function items(Request $request)
    {
        if($request->ajax())
        {
            $cart_items = Cart::instance('items')->content();

            return view(
                'front.pages.cart.partials.items',
                compact(
                    'cart_items'
                )
            )->render();
        }

        return back();
    }

    public function shippingMethods(Request $request)
    {
        if($request->ajax())
        {
            $user = auth()->user();

            $shipping_methods = ($user) ? $this->shipping_repo->availableByCountry($user->shippingCountry->id) : null;

            // selected shipping method
            $shipping_cart = Cart::instance('shipping');
            $shipping_items = $shipping_cart->content();
            $selected_shipping_method_id = ($shipping_items->count()) ? $shipping_cart->content()->first()->id : null;

            // items weight
            $items_weight = Cart::weight();

            return view(
                'front.pages.cart.partials.shipping-methods',
                compact(
                    'user',
                    'shipping_methods',
                    'items_weight',
                    'selected_shipping_method_id'
                )
            )->render();
        }

        return back();
    }

    /**
     * Renders the cart summary partial.
     *
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function summary(Request $request)
    {
        if($request->ajax())
        {
            $voucher_cart = Cart::instance('voucher')->content();
            $items_total = Cart::instance('items')->total();
            $shipping_total = Cart::instance('shipping')->total();
            $voucher_discount = Cart::instance('voucher')->total();
            $points_discount = Cart::instance('points')->total();
            $cart_tax = Cart::taxes();
            $cart_total = Cart::globalTotal();

            $items_total = Price::output($items_total);
            $shipping_total = Price::output($shipping_total);
            $voucher_discount = Price::output($voucher_discount);
            $points_discount = Price::output($points_discount);
            $cart_tax = Price::output($cart_tax);
            $cart_total = Price::output($cart_total);

            return view(
                'front.pages.cart.partials.summary',
                compact(
                    'voucher_cart',
                    'items_total',
                    'shipping_total',
                    'voucher_discount',
                    'points_discount',
                    'cart_tax',
                    'cart_total'
                )
            )->render();
        }

        return back();
    }

    /**
     * Shows the checkout conclude page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function conclude($order_id)
    {
        $page = Page::find(17);

        $payment_methods = PaymentMethod::all();

        $order = Order::with(
            [
                'user',
                'paymentReference'
            ]
        )
        ->where('id', $order_id)
        ->first();


        // clean cart
        Cart::globalDestroy();

        return view(
            'front.pages.cart.conclude',
            compact(
                'page',
                'payment_methods',
                'order'
            )
        );
    }
}
