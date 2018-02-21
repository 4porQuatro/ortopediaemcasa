<?php

namespace App\Models\Geo;

use App\Lib\Model;

class Country extends Model
{
	protected $table = "geo_countries";

	/**
	 *	Get country's cities
	 *	@return Relation $cities
	 */
	public function cities()
	{
		return $this->hasMany(City::class);
	}
}
