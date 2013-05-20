<?php

if (count($errors) > 0)
{
	echo '
	<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>Error</strong> ';
	foreach ($errors as $error)
	{
		echo $error.'<br/>';
	}
	echo '
	</div>';
}

?>

<h1>Sign in</h1>

<form class="login-form custom" method="post" action="<?php echo BASE_URL; ?>user/login" novalidate>
	<label for="login">Login</label>
	<input type="email" name="login" id="login" required />
	<label for="password">Password</label>
	<input type="password" name="password" id="password" required />
	<button type="submit" class="btn">Sign in</button>
</form>