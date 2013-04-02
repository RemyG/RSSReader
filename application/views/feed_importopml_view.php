<div id="content" class="padding-20">

	<h1>Import feeds from an OPML file</h1>

	<form class="feed-form" method="post" enctype="multipart/form-data">
		<label for="opmlfile">OPML file</label>
		<input type="file" name="opmlfile" id="opmlfile">
		<input type="submit" name="submit" value="Import file">
	</form>

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