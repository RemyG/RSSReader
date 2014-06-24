<?php

class Session_helper {

	function set($key, $val)
	{
		$_SESSION["$key"] = $val;
	}

	function get($key)
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

	function destroy()
	{
		session_destroy();
	}

	function getCurrentUser()
	{
		if ($this->get('user-login') != null)
		{
			return $this->get('user-login');
		}
		else
		{
			return null;
		}
	}
}

?>