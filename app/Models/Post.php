<?php

namespace App\Models;

use App\Lib\Model;

use App\Scopes\LanguageIdScope;
use App\Scopes\ActiveScope;

class Post extends Model
{
	protected $dates = [
		'published_at',
		'created_at',
		'updated_at'
	];

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
	 *	Get language
	 *
	 *	@return language
	 */
	public function language()
	{
		return $this->belongsTo(Language::class);
	}

	/**
	 * Get related posts
	 *
	 * @return Collection
	 */
	public function related()
	{
		return $this->belongsToMany(Post::class, 'posts_related', 'post_id', 'related_post_id');
	}


	/*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

	public function getPrev()
	{
		return self::where('published_at', '<', $this->published_at)
					->orderBy('published_at', 'DESC')
					->first();
	}

	public function getNext()
	{
		return self::where('published_at', '>', $this->published_at)
					->orderBy('published_at', 'ASC')
					->first();
	}
}
