<div id="content">

	<form method="post" enctype="multipart/form-data">
		<input type="file" name="opmlfile" id="opmlfile"><br>
		<input type="submit" name="submit" value="Submit">
	</form>

	<a href="/">Home</a>

	<?php
		if (isset($errors) && count($errors) > 0)
		{
			foreach ($errors as $error)
			{
				echo '<p>'.$error.'</p>';
			}
		}
	?>

</div>