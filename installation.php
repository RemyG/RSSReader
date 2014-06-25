<?php

$updated = false;
$installed = false;

$SRC_DIR = 'src';

$str = $str = file_get_contents($SRC_DIR.'/config/config.php');
if (preg_match("/define\('INSTALLED', 'true'\);/", $str))
{
	$installed = true;
}

if (!$installed
	&& array_key_exists('baseurl', $_POST)
	&& array_key_exists('dbpassword', $_POST)
	&& array_key_exists('dbuser', $_POST)
	&& array_key_exists('dbname', $_POST)
	&& array_key_exists('dbhost', $_POST))
{

	$str = file_get_contents($SRC_DIR.'/config/config.php');
	$baseUrl = $_POST['baseurl'];
	if (substr($baseUrl, -strlen("/")) !== "/")
	{
		$baseUrl = $baseUrl."/";
	}
	$str = preg_replace("/define\('BASE_URL', '.*?'\);/", "define('BASE_URL', '".$baseUrl."');", $str);
	file_put_contents($SRC_DIR.'/config/config.php', $str);

	//read the entire string
	$str = file_get_contents($SRC_DIR.'/build/conf/rss-reader-conf.php');

	$str = preg_replace("/'mysql:host=(.*?);dbname=(.*?)',/", "'mysql:host=".$_POST['dbhost'].";dbname=".$_POST['dbname']."',", $str);
	$str = preg_replace("/'user' => '(.*?)',/", "'user' => '".$_POST['dbuser']."',", $str);
	$str = preg_replace("/'password' => '(.*?)',/", "'password' => '".$_POST['dbpassword']."',", $str);

	file_put_contents($SRC_DIR.'/build/conf/rss-reader-conf.php', $str);

	$db = new PDO("mysql:host=".$_POST['dbhost'].";dbname=".$_POST['dbname'], $_POST['dbuser'], $_POST['dbpassword']);
	$sql = file_get_contents($SRC_DIR.'/build/sql/schema.sql');
	$qr = $db->exec($sql);
	$sql = file_get_contents($SRC_DIR.'/build/sql/insert.sql');
	$qr = $db->exec($sql);

	$str = file_get_contents($SRC_DIR.'/config/config.php');
	$str = preg_replace("/define\('INSTALLED', '.*?'\);/", "define('INSTALLED', 'true');", $str);
	file_put_contents($SRC_DIR.'/config/config.php', $str);

	$updated = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />

	<meta name="viewport" content=" width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />

	<title>RSS Reader - Installation</title>

	<link rel="stylesheet" href="/static/css/bootstrap.min.css" />

	<style type="text/css">
		div.content {
			margin: 20px auto;
			width: 300px;
		}
		input {
			width: 100%;
		}
		input[type=submit] {
			margin-top: 15px;
			width: 100%;
		}

	</style>

</head>

<body>

	<div class="content">

		<header><h1>RSS Reader<br/>Installation</h1></header>

		<?php if ($installed) { ?>

			<p>RSS Reader is already installed.</p>
			<p>To install again, edit <pre><code>application/config/config.php</code></pre>
				and replace <pre><code>define('INSTALLED', 'true');</code></pre>
				by <pre><code>define('INSTALLED', 'false');</code></pre></p>
			<p><a href="/">Go to homepage</a></p>

		<?php } else if ($updated) { ?>

				<div class="alert alert-success">
					RSS Reader has been successfully installed!
				</div>

				<p><a class="btn btn-success" href="<?php echo $baseUrl; ?>">Go to homepage</a></p>

		<?php } else { ?>

			<form method="post">
				<fieldset>
					<legend>Site configuration</legend>
					<label for="baseurl">Site Base URL</label>
					<input type="url" name="baseurl" id="baseurl" placeholder="http://rss.yourhost.com" autofocus required />
				</fieldset>
				<fieldset>
					<legend>Database configuration</legend>
					<label for="dbhost">DB Host</label><input type="text" name="dbhost" id="dbhost" placeholder="localhost" required />
					<label for="dbname">DB Name</label><input type="text" name="dbname" id="dbname" placeholder="rss-reader" required />
					<label for="dbuser">DB Username</label><input type="text" name="dbuser" id="dbuser" placeholder="mysqluser" required />
					<label for="dbpassword">DB Password</label><input type="password" name="dbpassword" id="dbpassword" placeholder="mysql-password" />
				</fieldset>
				<fieldset>
					<input type="submit" class="btn btn-large btn-primary" value="Install RSS Reader" />
				</fieldset>
			</form>

		<?php } ?>

	</div>

</body>

</html>