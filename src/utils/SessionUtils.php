<?php

class SessionUtils {

	public static function set($key, $val)
	{
		$_SESSION["$key"] = $val;
	}

	public static function get($key)
	{
		if (array_key_exists($key, $_SESSION))
		{
			return $_SESSION["$key"];
		}
		else
		{
			return null;
		}
	}

	public static function destroy()
	{
		session_destroy();
	}

	public static function getCurrentUser()
	{
		return SessionUtils::get('user-login');
	}
}

?>