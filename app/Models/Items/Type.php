<?php

namespace App\Models\Items;

use App\Lib\Model;

use App\Traits\LapBootTrait;

class Type extends Model
{
	use LapBootTrait;

	protected $table = "items_types";

	/**
	 *	Get language
	 *
	 *	@return Relation
	 */
	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class);
	}

	/**
	 * Get type's categories
	 *
	 * @return Relation
	 */
	public function categories(){
		return $this->hasMany(Category::class);
	}
}
