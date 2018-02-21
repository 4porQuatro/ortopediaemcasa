<?php

namespace App\Models\Items;

use App\Lib\Model;

use App\Traits\LapBootTrait;

class Size extends Model
{
	use LapBootTrait;

	protected $table = "items_sizes";

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
	 * Get sizes's items
	 *
	 * @return Relation
	 */
	public function items()
	{
		return $this->belongsToMany(Item::class, 'items_stocks', 'size_id', 'item_id')->withPivot('stock');
	}
}
