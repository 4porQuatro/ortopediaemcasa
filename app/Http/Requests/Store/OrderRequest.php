<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\AppFormRequest;

use Gloudemans\Shoppingcart\Facades\Cart;

class OrderRequest extends AppFormRequest
{
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'order';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return sizeof(Cart::instance('items')->content()) > 0;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shipping_method_id' => 'required|exists:store_shipping_methods,id',
            'voucher' => 'nullable|exists:store_vouchers,code|validVoucher',
            'points' => 'numeric|min:0|max:' . auth()->user()->getAvailablePoints()
        ];
    }
}
