<?php
	namespace App\Models;

	use App\Lib\Model;
    use App\Scopes\LanguageIdScope;

    class EmailMessage extends Model
	{
        /**
         * The "booting" method of the model.
         *
         * @return void
         */
        protected static function boot()
        {
            parent::boot();

            static::addGlobalScope(new LanguageIdScope());
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
		 * The roles that belong to the user.
		 */
		public function receivers(){
			return $this->belongsToMany(EmailReceiver::class);
		}

		public function getParsedMessage(array $data){
			if (is_object($data)) {
		        $data = get_object_vars($data);
		    }
		    $map = array_flip(array_keys($data));
			$new_str = preg_replace_callback(
						'/(^|[^%])%([a-zA-Z0-9_-]+)\$/',
						function($m) use ($map) {
							return $m[1].'%'.($map[$m[2]] + 1).'$';
						},
						$this->message
					);
			return vsprintf($new_str, $data);
			//return vsprintf($this->message, $data);
		}
	}
