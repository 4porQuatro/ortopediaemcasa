<?php

namespace App\Models\Pages;

use App\Lib\Model;

use App\Scopes\LanguageIdScope;
use App\Scopes\ActiveScope;

class Page extends Model
{
	/**
	 * The "booting" method of the model.
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope(new LanguageIdScope);
		static::addGlobalScope(new ActiveScope);
	}

	/**
	 *	Get pages's language
	 *
	 *	@return Language
	 */
	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class);
	}

	/**
	 * Get page's articles
	 *
	 * @param relation
	 */
	public function articles(){
		return $this->hasMany(Article::class);
	}
}
