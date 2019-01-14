<?php
namespace App\Lib;

use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent{
	public function hasAttribute($attr){
		return array_key_exists($attr, $this->attributes);
	}

	private static function getCalledClass()
	{
		$class = get_called_class();
		return new $class;
	}

	public static function exists($key, $value)
	{
		return self::where($key, '=', $value)->exists();
	}



	/*
	|--------------------------------------------------------------------------
	| Records
	|--------------------------------------------------------------------------
	*/

	public static function getRec($where = [])
	{
		$called_class = self::getCalledClass();

		$where['active'] = 1;

		return self::where($where)->first();
	}

	public static function getRecs($where = [])
	{
		$called_class = self::getCalledClass();

		$where['active'] = 1;

		return self::where($where)
					->orderBy('priority')
					->get();
	}


	/*
    |--------------------------------------------------------------------------
    | Images
    |--------------------------------------------------------------------------
    */

	/**
	 * Get record's images
	 * @param  string $type
	 * @return Array
	 */
	public function getImages($type = "")
	{
		$column = empty($type) ? "images" : $type . "_images";

		return json_decode($this->$column);
	}

	/**
	 * Get the URL for the record's images folder
	 *
	 * @return string
	 */
	public function getImagesUrl()
	{
		return url('/uploads/images') . '/' . $this->getTable() . "/";
	}

	/**
	 * Get record's first image path
	 *
	 * @param  string $type
	 *
	 * @return string $image_path
	 */
	public function getFirstImagePath($type = "")
	{
		$image_path = null;

		$images = $this->getImages($type);

		if(!empty($images))
		{
			$image_path = $this->getImagesUrl() . $images[0]->source;
		}

		return $image_path;
	}



	/*
    |--------------------------------------------------------------------------
    | Videos
    |--------------------------------------------------------------------------
    */

	/**
	 * Get record's videos
	 * @param  string $type
	 * @return Array
	 */
	public function getVideos($type = "")
	{
		$column = empty($type) ? "videos" : $type . "_videos";

		return json_decode($this->$column);
	}

	/**
	 * Get the URL for the record's videos folder
	 *
	 * @return string
	 */
	public function getVideosUrl()
	{
		return url('uploads/videos') . '/' . $this->getTable() . "/";
	}

	/**
	 * Get record's first video path
	 *
	 * @param  string $type
	 *
	 * @return string $video_path
	 */
	public function getFirstVideoPath($type = "")
	{
		$video_path = null;

		$videos = $this->getVideos($type);

		if(!empty($videos))
		{
			$video_path = $this->getVideosUrl() . $videos[0]->source;
		}

		return $video_path;
	}



	/*
    |--------------------------------------------------------------------------
    | Docs
    |--------------------------------------------------------------------------
    */

	/**
	 * Get record's documents
	 * @param  string $type
	 * @return Array
	 */
	public function getDocs($type = "")
	{
		$column = empty($type) ? "docs" : $type . "_docs";

		return json_decode($this->$column);
	}

	/**
	 * Get the URL for the record's docs folder
	 *
	 * @return string
	 */
	public function getDocsUrl()
	{
		return url('/uploads/files') . '/' . $this->getTable() . "/";
	}

	/**
	 * Get record's first doc path
	 *
	 * @param  string $type
	 *
	 * @return string $doc_path
	 */
	public function getFirstDocPath($type = "")
	{
		$doc_path = null;

		$docs = $this->getDocs($type);

		if(!empty($docs))
		{
			$doc_path = $this->getDocsUrl() . $docs[0]->source;
		}


		return $doc_path;
	}
}
