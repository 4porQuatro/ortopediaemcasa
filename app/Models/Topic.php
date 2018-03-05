<?php

namespace App\Models;

use App\Lib\Model;

use App\Traits\LapBootTrait;

class Topic extends Model
{
	use LapBootTrait;

	/**
	 *	Get article's language
	 *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function language()
	{
		return $this->belongsTo(Language::class);
	}

	/**
	 * Get category.
	 *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function topicsCategory(){
		return $this->belongsTo(TopicsCategory::class);
	}
}
