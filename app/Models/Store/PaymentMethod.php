<?php

namespace App\Models\Store;

use App\Lib\Model;
use App\Lib\Store\Price;

use App\Traits\LapBootTrait;


class PaymentMethod extends Model
{
	use LapBootTrait;

	protected $table = "store_payment_methods";

	/**
	 *	Get language
	 *
	 *	@return Language
	 */
	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class);
	}


    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

	/**
	 * Get formatted price
	 */
	public function getFormattedPriceAttribute(){
		return Price::output($this->price);
	}
}
