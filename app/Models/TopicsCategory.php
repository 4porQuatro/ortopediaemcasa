<?php

namespace App\Models;

use App\Lib\Model;

use App\Traits\LapBootTrait;

class TopicsCategory extends Model
{
    use LapBootTrait;

	/**
	 *	Get pages's language
	 *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function language()
	{
		return $this->belongsTo(Language::class);
	}

	/**
	 * Get topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function topics(){
		return $this->hasMany(Article::class);
	}
}
