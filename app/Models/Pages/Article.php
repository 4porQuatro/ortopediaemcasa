<?php

namespace App\Models\Pages;

use App\Lib\Model;

use App\Traits\LapBootTrait;

class Article extends Model
{
	use LapBootTrait;

	protected $table = "page_articles";

	/**
	 *	Get article's language
	 *
	 *	@return Language
	 */
	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class);
	}

	/**
	 * Get article's page
	 *
	 * @return Page
	 */
	public function page(){
		return $this->belongsTo(Page::class);
	}
}
