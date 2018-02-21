<?php

namespace App\Models\Store;

use App\Lib\Model;

use App\Scopes\LanguageIdScope;

class OrderState extends Model
{
	protected $table = "store_order_states";

	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope(new LanguageIdScope);
	}

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
