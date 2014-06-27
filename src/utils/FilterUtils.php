<?php

/**
 * @covers SessionUtils
 */
class FilterUtils extends PHPUnit_Framework_TestCase
{
	public static function sanitizeEntry($array, $key, $filter)
	{
		if (!array_key_exists($key, $array)) {
			return NULL;
		}
		return filter_var($array[$key], $filter);
	}

	public static function sanitizeVar($var, $filter)
	{
		return filter_var($var, $filter);
	}
}