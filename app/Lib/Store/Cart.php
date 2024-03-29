<?php

namespace App\Lib\Store;

use Gloudemans\Shoppingcart\Facades\Cart as GloudemansCart;

class Cart extends GloudemansCart
{
    /**
     * Calculates the total taxes.
     *
     * @return mixed
     */
    public static function taxes()
    {
        return self::instance('items')->tax() + self::instance('shipping')->tax();
    }

    /**
     * Calculates the total discounts.
     *
     * @return mixed
     */
    public static function discounts()
    {
        return self::instance('voucher')->total() + self::instance('points')->total();
    }

    /**
     * Calculates the cart global amount, including
     * shipping costs and discounts.
     *
     * @return mixed
     */
    public static function globalTotal()
    {
        return self::instance('items')->total() + self::instance('shipping')->total() - self::instance('voucher')->total() - self::instance('points')->total();
    }

    /**
     * Calculates the total weight of the items in the Cart.
     *
     * @return int
     */
    public static function weight()
    {
        $items_weight = 0;

        $cart_items = self::instance('items')->content();

        if($cart_items->count())
        {
            foreach($cart_items as $item)
            {
                $items_weight += $item->qty * $item->options->weight;
            }
        }

        return $items_weight;
    }

    public static function globalDestroy(){
        Cart::instance('items')->destroy();
        Cart::instance('shipping')->destroy();
        Cart::instance('voucher')->destroy();
    }
}
