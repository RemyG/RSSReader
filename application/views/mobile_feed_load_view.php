<div data-role="page">

	<div data-role="header">
		<h1><?php echo $feed->getTitle(); ?></h1>
	</div>

	<div data-role="content">

		<ul data-role="listview">

			<?php
	
			foreach ($entries as $entry) {
				
				echo '
					<li><a href="/m/entry/load/'.$entry->getId().'">'.$entry->getTitle().'</a></li>';
			}

			?>

		</ul>

	</div>

</div>