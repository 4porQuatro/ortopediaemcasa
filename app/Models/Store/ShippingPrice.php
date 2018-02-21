<?php

namespace App\Models\Store;

use App\Lib\Model;

class ShippingPrice extends Model
{
	protected $table = "store_shipping_prices";

    /**
     * Get related method.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function method(){
        return $this->belongsTo(ShippingMethod::class);
    }

    /**
     * Get related shipping limit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function limit(){
        return $this->belongsTo(ShippingLimit::class);
    }

    /**
     * Get related zone.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function zone(){
        return $this->belongsTo(ShippingZone::class);
    }
}