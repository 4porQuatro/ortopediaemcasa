<?php

namespace App\Http\Requests\Cart;

use App\Http\Requests\AppFormRequest;

use Gloudemans\Shoppingcart\Facades\Cart;

class ConcludeCheckoutRequest extends AppFormRequest
{
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'checkout';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return sizeof(Cart::content()) > 0;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment_method_id' => 'required|exists:store_payment_methods,id',
            'shipping_method_id' => 'required|exists:store_shipping_methods,id',
            'voucher' => 'exists:store_vouchers,code|validVoucher'
        ];
    }
}
