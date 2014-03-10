<?php

/**
 * Main class
 *
 * @author RemyG
 * @license MIT
 */
function pfp()
{

	// Set our defaults
	$controller = DEFAULT_CONTROLLER;
	$action = 'index';
	$url = '';

	// Get request url and script url
	$request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
	$script_url  = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';

	// Get our url path and trim the / of the left and the right
	if($request_url != $script_url) $url = trim(preg_replace('/'. str_replace('/', '\/', str_replace('index.php', '', $script_url)) .'/', '', $request_url, 1), '/');

	// Split the url into segments
	$segments = explode('/', $url);

	require_once(APP_DIR .'helpers/session_helper.php');
	$sessionHelper = new Session_helper();

	$mobile = '';
	$useMobile = false;
	if (isset($segments[0]) && $segments[0] == 'm')
	{
		$mobile = 'm/';
		$useMobile = true;
		$sessionHelper->set('mobile', '1');
		if(isset($segments[1]) && $segments[1] != '') $controller = $segments[1];
		if(isset($segments[2]) && $segments[2] != '') $action = $segments[2];
		$arguments = array_slice($segments, 3);
	}
	else
	{
		if(isset($segments[0]) && $segments[0] != '') $controller = $segments[0];
		if(isset($segments[1]) && $segments[1] != '') $action = $segments[1];
		$arguments = array_slice($segments, 2);
	}

	if ($controller != 'install' && !($controller == 'user' && $action == 'login') && !($controller == 'feed' && ($action == 'updateall' || $action == 'forceupdateall')))
	{
		$currentUser = $sessionHelper->getCurrentUser();
		if ($currentUser == null)
		{
			header('Location: '. BASE_URL . $mobile . 'user/login');
		}
	}

	if ($useMobile)
	{
		$controller = 'mobile'.$controller;
	}

	// Get our controller file
		$path = APP_DIR . 'controllers/' . $controller . '.php';
	if(file_exists($path)){
				require_once($path);
	} else {
				$controller = ERROR_CONTROLLER.'Controller';
				require_once(APP_DIR . 'controllers/' . $controller . '.php');
	}

	$controller = $controller.'Controller';

		// Check the action exists
		if(!method_exists($controller, $action)
		{
				$controller = ERROR_CONTROLLER.'Controller';
				require_once(APP_DIR . 'controllers/' . $controller . '.php');
				$action = 'index';
		}

	// Create object and call method
	$obj = new $controller;

	die(call_user_func_array(array($obj, $action), $arguments));
}

?>
