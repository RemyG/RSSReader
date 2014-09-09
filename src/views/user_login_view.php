<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content=" width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
	<title><?php echo isset($pageTitle) ? $pageTitle : DEFAULT_TITLE; ?></title>

	<link rel="stylesheet" href="/static/css/style.css" type="text/css" media="screen" />

	<style type="text/css">
		div#content {
			margin-left: 325px;
		}
	</style>

</head>
<body>

<script type="text/javascript">

if (screen.width <= 699) {
document.location = "<?php echo BASE_URL; ?>m/user/login";
}

</script>

<div id="main-container">

	<nav id="header" class="navbar navbar-fixed-top">
		<div class="navbar">
			<div class="navbar-inner">
				<a class="brand" href=""><?php echo PROJECT_NAME; ?></a>
			</div>
		</div>
	</nav>

	<div id="content">

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

		<form method="post" novalidate>
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
</div>

</body>
</html>