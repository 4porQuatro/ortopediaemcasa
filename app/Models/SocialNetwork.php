<?php
	namespace App\Models;

	use App\Lib\Model;

    use App\Scopes\ActiveScope;
    use App\Scopes\PrioritySortScope;

	class SocialNetwork extends Model
	{
		/**
		 * The "booting" method of the model.
		 *
		 * @return void
		 */
		protected static function boot()
		{
			parent::boot();

			static::addGlobalScope(new ActiveScope);
			static::addGlobalScope(new PrioritySortScope);
		}
	}
