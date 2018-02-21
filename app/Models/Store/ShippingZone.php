<?php

namespace App\Models\Store;

use App\Lib\Model;

class ShippingZone extends Model
{
	protected $table = "store_shipping_zones";

    /**
     * Get related shipping method.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function method(){
        return $this->belongsTo(ShippingMethod::class);
    }

    /**
     * Get related countries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function countries(){
        return $this->hasMany(\App\Models\Geo\Country::class, 'store_shipping_countries');
    }
}