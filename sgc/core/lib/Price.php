<?php

class Price
{
	public static function output($value, $currency = "€")
	{
		return  number_format($value, 0, ',', '.') . $currency;
	}
}
