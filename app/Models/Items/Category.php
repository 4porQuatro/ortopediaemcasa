<?php

namespace App\Models\Items;

use App\Lib\Model;

use App\Traits\LapBootTrait;

class Category extends Model
{
	use LapBootTrait;

	protected $table = "items_categories";

	/**
	 *	Get language
	 *
	 *	@return Language
	 */
	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class);
	}

	/**
	 *	Get category's type
	 *
	 *	@return Type
	 */
	public function type()
	{
		return $this->belongsTo(Type::class);
	}

	/**
	 * Get category's items
	 *
	 * @return Relation
	 */
	public function items(){
		return $this->hasMany(Item::class);
	}
}
