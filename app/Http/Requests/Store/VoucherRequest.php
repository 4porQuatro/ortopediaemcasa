<?php

namespace App\Http\Requests\Store;

use App\Http\Requests\AppFormRequest;

class VoucherRequest extends AppFormRequest
{
    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'voucher';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'voucher' => 'required|exists:store_vouchers,code|validVoucher'
        ];
    }
}
