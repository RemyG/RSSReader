<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content=" width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
	<title><?php echo isset($pageTitle) ? $pageTitle : DEFAULT_TITLE; ?></title>

	<link rel="stylesheet" href="/static/css/login.css" type="text/css" media="screen" />

</head>
<body>

<script type="text/javascript">

if (screen.width <= 699) {
document.location = "<?php echo BASE_URL; ?>m/user/login";
}

</script>

<div id="main-container">

	<div id="header">
		<h1><?php echo PROJECT_NAME; ?></h1>
	</div>

	<div id="login-form">
		<form method="post" novalidate="novalidate" class="login-form">
			<div class="form-group">
			<input type="text" name="login" id="login" placeholder="Login" required />
			</div>
			<div class="form-group">
			<input type="password" name="password" id="password" placeholder="Password" required />
			</div>
			<div class="form-group">
			<button type="submit" class="btn">Sign in</button>
			</div>
		</form>
		<?php

		if (count($errors) > 0)
		{
			echo '<div id="login-error">';
			echo '<p>';
			foreach ($errors as $error)
			{
				echo $error.'<br/>';
			}
			echo '</p>';
			echo '</div>';
		}

		?>
	</div>
</div>

</body>
</html>