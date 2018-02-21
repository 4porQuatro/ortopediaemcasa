<?php
	class DataValidator
	{
		public static function isEmail($value)
		{
			return filter_var($value, FILTER_VALIDATE_EMAIL);
		}

		public static function isURL($value)
		{
			return filter_var($value, FILTER_VALIDATE_URL) || $value == '#';
		}

		public static function isInt($value)
		{
			return filter_var($value, FILTER_VALIDATE_INT);
		}

		public static function isFloat($value)
		{
			return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
		}

		public static function isSecurePassword($value)
		{
			return strlen($value) >= 8;
		}

		public function __construct(){}
	}
