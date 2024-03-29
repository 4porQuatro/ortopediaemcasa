<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Requests\Cart\AddItemRequest;
use App\Http\Requests\Cart\ConcludeCheckoutRequest;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use App\Lib\Store\Cart;
use App\Lib\Store\Price;

use App\Models\Items\Item;
use App\Models\Items\ItemAttributeType;
use App\Models\Items\ItemAttributeValue;
use App\Models\Store\ShippingMethod;
use App\Models\Store\Voucher;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['conclude', 'addShippingMethod']);
    }

    /**
     * Adds an item to the cart.
     *
     * @param AddItemRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add(AddItemRequest $request)
    {
        $item = Item::find($request->item_id);

        $item_data = [
            'category' => [
                'id' => $item->itemCategory->id,
                'name' => $item->itemCategory->title
            ],
            'formatted_price' => Price::output($item->price),
            'points' => $item->points,
            'weight' => $item->weight,
            'image_path' => asset($item->getFirstImagePath('list')),
            'url' => urli18n('product', $item->slug)
        ];

        if($request->has('item_attr') ){
            foreach($request->get('item_attr') as $attribute_type_id => $attribute_value_id){
                $attribute_type = ItemAttributeType::find($attribute_type_id);
                $attribute_value = ItemAttributeValue::find($attribute_value_id);

                $item_data['attributes'][] = [
                    'name' => $attribute_type->title,
                    'value' => $attribute_value->title
                ];
            }
        }

        $cart_item = Cart::instance('items')->add(
            $item,
            $request->quantity,
            $item_data
        );

        $cart_item->setTaxRate($item->tax->percentage);
        $this->refreshDiscounts();

        return redirect(urli18n('checkout'));
    }

    /**
     * Removes an item from the cart.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        Cart::instance('items')->remove($request->row_id);

        $this->refreshShippingMethods();
        $this->refreshDiscounts();
    }


    /**
     * Updates a cart item quantity.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $cart = Cart::instance('items');
        $row_id = $request->row_id;
        $qty = $request->quantity;

        $item = $cart->search(function ($cart_item, $row_id) {
            return $cart_item->rowId === $row_id;
        });

//        if ($item[$row_id]->options->stock >= $qty) {
//            $cart->update($request->row_id, $request->quantity);
//        }

        $cart->update($request->row_id, $request->quantity);

        $this->refreshShippingMethods();
        $this->refreshDiscounts();
    }

    /**
     * Adds a shipping method to cart.
     *
     * @param Request $request
     */
    public function addShippingMethod(Request $request)
    {
        $this->setShippingMethod($request->shipping_method_id);
    }

    /**
     * Sets the shipping method.
     *
     * @param $shipping_method_id
     */
    private function setShippingMethod($shipping_method_id)
    {
        $shipping_method = ShippingMethod::find($shipping_method_id);
        $user = auth()->user();
        $items_weight = Cart::weight();

        Cart::instance('shipping')->destroy();

        $cart_item = Cart::instance('shipping')->add(
            $shipping_method->id,
            $shipping_method->name,
            $shipping_method->price($items_weight, $user->shippingCountry->id) / (1 + ($shipping_method->tax->percentage / 100)),
            1
        );
        $cart_item->setTaxRate($shipping_method->tax->percentage);
    }

    /**
     * Refreshes the shipping method.
     */
    private function refreshShippingMethods()
    {
        $shipping_cart = Cart::instance('shipping');
        $shipping_items = $shipping_cart->content();

        if (auth()->check() && $shipping_cart->content()->count()) {
            $shipping_method_id = current(current($shipping_items))->id;

            $this->setShippingMethod($shipping_method_id);
        }
    }

    /**
     * Refreshes the voucher discount.
     */
    private function refreshDiscounts()
    {
        // update voucher discount
        $voucher_cart = Cart::instance('voucher');
        $voucher_items = $voucher_cart->content();

        if ($voucher_items->count()) {
            $voucher_code = current(current($voucher_items))->name;

            $discount = Voucher::calculateDiscount($voucher_code, Cart::instance('items')->content());

            // reset voucher cart
            Cart::instance('voucher')->destroy();

            // add discount to cart
            $cart_item = Cart::instance('voucher')->add(1, $voucher_code, 1, $discount);

            // set taxes = 0
            $cart_item->setTaxRate(0);
        }
    }
}
