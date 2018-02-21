<?php

namespace App\Models\Geo;

use App\Lib\Model;

class City extends Model
{
	protected $table = "geo_cities";

	/**
	 *	Get city's country
	 *	@return Relation $country
	 */
	public function country()
	{
		return $this->belongsTo(Country::class);
	}
}
