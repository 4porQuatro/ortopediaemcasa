<?php

namespace App\Http\Controllers\Store;

use App\Http\Requests\Store\PointsRequest;
use App\Http\Controllers\Controller;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Stores the amount of points a user wants to use on a purchase.
     *
     * @param PointsRequest $request
     * @return mixed
     */
    public function add(PointsRequest $request)
    {
        // reset points cart
        Cart::instance('points')->destroy();

        // add points discount to cart
        $cart_item = Cart::instance('points')->add(1, "Desconto pontos", $request->get('points'), .1);

        // set taxes = 0
        $cart_item->setTaxRate(0);

        if($request->ajax())
        {
            return response()->json(['success' => trans('app.points-added')]);
        }

        return back();
    }

    /**
     * Removes the points a user want to use.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        // reset points cart
        Cart::instance('points')->destroy();

        if($request->ajax())
        {
            return response()->json(['success' => 'Pontos removidos.']);
        }

        return back();
    }
}
