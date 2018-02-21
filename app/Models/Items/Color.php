<?php

namespace App\Models\Items;

use App\Lib\Model;

use App\Traits\LapBootTrait;

class Color extends Model
{
	use LapBootTrait;

	protected $table = "items_colors";

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
	 * Get colors's items
	 *
	 * @return Relation
	 */
	public function items()
	{
		return $this->belongsToMany(Item::class, 'items_stocks', 'color_id', 'item_id');
	}
}
