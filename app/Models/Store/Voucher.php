<?php

namespace App\Models\Store;

use App\Lib\Model;
use App\Models\Items\ItemsCategory;

class Voucher extends Model
{
    protected $table = "store_vouchers";

    protected $dates = [
	    'expires_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Get related item category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ItemsCategory::class, 'category_id');
    }

    /**
     * Calculates the voucher discount.
     *
     * @param $code
     * @param $items
     * @return int
     */
    public static function calculateDiscount($code, $items)
    {
        $discount = 0;

        $voucher = self::where('code', $code)->first();

        if($voucher)
        {
            $incidence_value = 0;

            foreach($items as $item)
            {
                if($voucher->category_id == $item->options->category['id'])
                {
                    $incidence_value += $item->total;
                }
            }

            // If the incidence value is greater than 0, then we can
            // calculate the discount for the current purchase.
            if($incidence_value > 0)
            {
                $discount = ($voucher->percentage) ? $incidence_value * ($voucher->value / 100) : $voucher->value;
            }

            // At last, we must check if the incidence value is lower than the discount.
            // If it is, the discount should be equal to the incidence value.
            if($incidence_value < $discount)
            {
                $discount = $incidence_value;
            }
        }

        return $discount;
    }
}
