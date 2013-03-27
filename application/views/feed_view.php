<div id="content">

	<a href="">Home</a>

	<?php
		foreach ($feed->getEntrys() as $entry) {
			echo '<p>'.$entry->getTitle().'</p>';
		}
	?>

</div>