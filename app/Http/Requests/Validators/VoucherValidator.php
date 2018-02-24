<?php

namespace App\Http\Requests\Validators;

use App\Models\Store\Voucher;


class VoucherValidator
{
    /**
     * Verifies if a voucher is valid.
     *
     * @param $attribute    the input name
     * @param $value        the input value
     * @param $parameters   the parameters passed on validation rule
     * @param $validator    the validator instance
     * @return bool
     */
    public function valid($attribute, $value, $parameters, $validator)
    {
        $voucher = Voucher::where('code', $value)->first();

        return $validator->errors()->count() || ($voucher && strtotime($voucher->expires_at) >= strtotime(date('Y-m-d')));
    }
}
