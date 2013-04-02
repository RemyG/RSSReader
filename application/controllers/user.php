<?php

class UserController extends Controller
{
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
					var_dump($user);
					if ($passwordHashed == $user->getPassword())
					{
						session_start();
						$sessionHelper = $this->loadHelper('Session_helper');
						$sessionHelper->set('user-login', $user->getLogin());
						$this->redirect('');
					}
				}
			}
			else
			{
				$template = $this->loadView('user_login_view');
				$template->render();
			}
		}
		else
		{
			$template = $this->loadView('install_view');
			$template->render();
		}
	}

	function logout()
	{
		$sessionHelper = $this->loadHelper('Session_helper');
		$sessionHelper->destroy();
		$this->redirect('');
	}
}

?>
