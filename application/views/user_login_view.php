<script type="text/javascript">

if (screen.width <= 699) {
document.location = "<?php echo BASE_URL; ?>m/user/login";
}

</script>

<div id="content" class="content-center">

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

	<form class="login-form custom form-block" method="post" action="<?php echo BASE_URL; ?>user/login" novalidate>
		<fieldset>
			<div class="field-group">
				<label for="login">Login</label>
				<input type="email" name="login" id="login" required />
			</div>
			<div class="field-group">
				<label for="password">Password</label>
				<input type="password" name="password" id="password" required />
			</div>
			<div class="field-group">
				<button type="submit" class="btn">Sign in</button>
			</div>
		</fieldset>
	</form>

</div>