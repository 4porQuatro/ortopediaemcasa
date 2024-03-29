<?php

namespace App\Http\Controllers\Store;

use App\Http\Requests\Store\VoucherRequest;
use App\Http\Controllers\Controller;

use Gloudemans\Shoppingcart\Facades\Cart;

use App\Models\Store\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function __construct(){
    }

    /**
     * Validates a voucher and adds it to the corresponding cart instance.
     *
     * @param VoucherRequest $request
     * @return mixed
     */
    public function add(VoucherRequest $request)
    {
        $discount = Voucher::calculateDiscount($request->voucher, Cart::instance('items')->content());

        // reset voucher cart
        Cart::instance('voucher')->destroy();

        // add discount to cart
        $cart_item = Cart::instance('voucher')->add(1, $request->voucher, 1, $discount);

        // set taxes = 0
        $cart_item->setTaxRate(0);

        if($request->ajax())
        {
            return response()->json(['success' => 'Voucher adicionado.']);
        }

        return back();
    }

    /**
     * Validates a voucher.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        // reset voucher cart
        Cart::instance('voucher')->destroy();

        if($request->ajax())
        {
            return response()->json(['success' => 'Voucher removido.']);
        }

        return back();
    }
}
