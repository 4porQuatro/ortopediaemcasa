<?php

namespace App\Models\Store;

use App\Traits\LapBootTrait;

use App\Lib\Model;

class Tax extends Model
{
	use LapBootTrait;

	protected $table = "store_taxes";

	/**
	 *	Get language
	 *
	 *	@return Language
	 */
	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class);
	}
}
