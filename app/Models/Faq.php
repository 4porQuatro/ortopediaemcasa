<?php
	namespace App\Models;

	use App\Lib\Model;

	use App\Traits\LapBootTrait;

	class Faq extends Model
	{
		use LapBootTrait;

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
?>
