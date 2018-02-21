<?php

namespace App\Models\Store;

use App\Lib\Model;

class ShippingLimit extends Model
{
	protected $table = "store_shipping_limits";

    /**
     * Get related shipping method.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function method(){
        return $this->belongsTo(ShippingMethod::class);
    }
}