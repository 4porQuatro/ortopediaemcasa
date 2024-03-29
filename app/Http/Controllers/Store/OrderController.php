<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\OrderRequest;

use App\Lib\Store\Cart;

use App\Models\Store\Order;
use App\Models\Store\OrderItem;
use App\Models\Store\ShippingMethod;
use App\Packages\Store\Events\OrderPlaced;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Concludes an order request.
     *
     * @param OrderRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(OrderRequest $request)
    {
        $shipping_method = ShippingMethod::find($request->shipping_method_id);

        $user = auth()->user();

        /* save order */
        $order = new Order();
        $order->user_id = $user->id;
        $order->shipping_method = $shipping_method->name;
        $order->shipping_observations = $shipping_method->final_message;
        $order->points_spent = $request->has('points') ? $request->get('points') : 0;

        // amounts
        $order->items_total = Cart::instance('items')->total();
        $order->shipping_cost = Cart::instance('shipping')->total();
        $order->taxes = Cart::taxes();
        $order->voucher_discount = Cart::instance('voucher')->total();
        $order->points_discount = Cart::instance('points')->total();
        $order->total = Cart::globalTotal();

        $order->save();

        /* save items */
        $cart_items = Cart::instance('items')->content();
        $points_earned = 0;

        foreach ($cart_items as $cart_item)
        {
            $item = new OrderItem();
            $item->item_id = $cart_item->id;
            $item->language_id = $shipping_method->language_id;
            $item->name = $cart_item->name;
            $item->attributes = json_encode($cart_item->options);
            $item->item_url = $cart_item->options->url;
            $item->image_url = $cart_item->options->image_path;
            $item->quantity = $cart_item->qty;
            $item->price = $cart_item->price;
            $item->tax = $cart_item->tax;
            $item->taxed_price = $cart_item->priceTax;
            $item->order_id = $order->id;

            $item->save();

            // increment earned points
            $points_earned += $cart_item->qty * $cart_item->options->points;
        }

        // update user points
        $order->points_earned = $points_earned;
        $order->save();

        /* trigger events */
        event(new OrderPlaced($order));

        return redirect()->action('CheckoutController@conclude', ['order_id' => $order->id]);
    }
}
