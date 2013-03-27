<div id="content">

	<form method="post">
		<input type="text" length="255" name="feed-url" id="feed-url" />
		<input type="submit" value="Submit" />
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