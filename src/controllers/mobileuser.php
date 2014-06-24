<?php

class MobileUserController extends Controller {

	function login()
	{
		$users = UserQuery::create()->find();

		if (count($users) > 0)
		{
			if (array_key_exists('password', $_POST) && array_key_exists('login', $_POST))
			{
				$login = $_POST['login'];
				$password = $_POST['password'];
				$user = UserQuery::create()->findOneByLogin($login);
				$passwordHashed = crypt($password, '$5$rounds=5000$'.md5($password).'$');
				if ($user != null)
				{
					if ($passwordHashed == $user->getPassword())
					{
						$sessionHelper = $this->loadHelper('Session_helper');
						$sessionHelper->destroy();
						session_start();
						$sessionHelper->set('user-login', $user->getLogin());
						$this->redirect('m/');
					}
				}
			}
			else
			{
				$template = $this->loadView('mobile_user_login_view');
				$template->renderMobile();
			}
		}
		else
		{
			$this->redirect('m/install');
		}
	}

	function logout()
	{
		$sessionHelper = $this->loadHelper('Session_helper');
		$sessionHelper->destroy();
		$this->redirect('m/');
	}
}

?>
