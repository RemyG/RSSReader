<div data-role="page">

	<div data-role="header">
		<h1>Sign in</h1>
	</div>

	<div data-role="content">

		<form class="login-form custom" method="post" action="<?php echo BASE_URL; ?>m/user/login" novalidate>
			<label for="login">Login</label>
			<input type="email" name="login" id="login" required />
			<label for="password">Password</label>
			<input type="password" name="password" id="password" required />
			<input type="submit" value="Sign in" class="button" />
		</form>

	</div>

</div>