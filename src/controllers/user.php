<?php

class UserController extends Controller
{
	function login()
	{
		$users = UserQuery::create()->find();

		$errors = array();

		if (count($users) > 0)
		{
			if (array_key_exists('password', $_POST) && array_key_exists('login', $_POST))
			{
				$login = $_POST['login'];
				$password = $_POST['password'];
				$user = UserQuery::create()->findOneByLogin($login);
				$passwordHashed = crypt($password, '$5$rounds=5000$'.md5($password).'$');
				if ($user != null && $passwordHashed == $user->getPassword())
				{
					SessionUtils::destroy();
					session_start();
					SessionUtils::set('user-login', $user->getLogin());
					$this->redirect('');
				}
				else
				{
					$errors[] = 'Login failed. Invalid username or password.';
				}
			}
			$template = $this->loadView('user_login_view');
			$template->set('errors', $errors);
			$template->renderViewOnly();
		}
		else
		{
			$this->redirect('install');
		}
	}

	function logout()
	{
		SessionUtils::destroy();
		$this->redirect('');
	}
}

?>
