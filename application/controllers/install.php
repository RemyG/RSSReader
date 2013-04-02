<?php

class InstallController extends Controller {
	
	function index()
	{
		$template = $this->loadView('install_view');
		$template->set('pageTitle', PROJECT_NAME.' - Installation');
		$template->set('pageDescription', 'Welcome to PFP - Main page');

		$users = UserQuery::create()->find();

		if (array_key_exists('password', $_POST) && array_key_exists('login', $_POST))
		{			
			if (count($users) == 0)
			{
				$login = $_POST['login'];
				$password = $_POST['password'];
				$user = new User();
				$user->setLogin($login);
				$passwordHashed = crypt($password, '$5$rounds=5000$'.md5($password).'$');
				$user->setPassword($passwordHashed);
				$user->save();
				$sessionHelper = new Session_helper();
				$sessionHelper->set('user-login', $user->getLogin());
			}
		}
		else if (count($users) == 0)
		{
			$template->render();
		}
  		
	}
    
}

?>
