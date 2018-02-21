<?php

namespace App\Lib\Store;

class Price{

	public static function output($value, $currency = "€"){
		return number_format($value, 2, ',', '.') . $currency;
	}
}
