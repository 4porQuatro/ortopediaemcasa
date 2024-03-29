<?php

namespace App\Models\Store;

use App\Lib\Model;
use App\Lib\Store\Price;

use App\Models\Geo\Country;
use App\Models\Language;

use App\Traits\LapBootTrait;

class ShippingMethod extends Model
{
    use LapBootTrait;

    protected $table = "store_shipping_methods";

    /**
     *    Get language
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }


    /**
     *    Get tax.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    /**
     * Get zones.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function zones()
    {
        return $this->hasMany(ShippingZone::class);
    }

    /**
     * Get countries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'store_shipping_countries', 'shipping_method_id', 'country_id');
    }

    /**
     * Get limits.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function limits()
    {
        return $this->hasMany(ShippingLimit::class);
    }

    /**
     * Get prices.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function prices()
    {
        return $this->hasManyThrough(ShippingPrice::class, 'App\Models\Store\ShippingLimit');
    }

    /**
     *	Get shipping final price.
     *
     * @param null $weight
     * @param null $country_id
     * @return int|mixed
     */
    public function price($weight = null, $country_id = null)
    {
        $cost = 0;

        if ($this->cost > 0)
        {
            $cost = $this->cost;
        }
        else
        {
            $country = $this->countries()->withPivot('zone_id')->where('id', $country_id)->first();

            $prices = $this->prices()->where('zone_id', $country->pivot->zone_id)->get();

            if (sizeof($prices)) {
                foreach ($prices as $price) {
                    $limit = ShippingLimit::find($price->shipping_limit_id);

                    if ($limit->lower_limit <= $weight) {
                        $cost = $price->price;
                    }
                }
            }
        }

        return $cost;
    }


    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get formatted price
     */
    public function formattedPrice($weight = null, $country_id = null)
    {
        return Price::output($this->price($weight, $country_id));
    }
}
