<?php

/**
 * PDO2 extension of PDO
 *
 * @author RemyG
 * @license MIT
 */
class PDO2 extends PDO
{
	private static $_instance;

	public function __construct()
	{
	
	}

	public static function getInstance()
	{
		if (!isset(self::$_instance))
		{
			try
			{
				self::$_instance = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);			
			}
			catch (PDOException $e)
			{
				echo $e;
			}
		}
		return self::$_instance;
	}
}