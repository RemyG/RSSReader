<div data-role="page">

	<div data-role="header" data-position="fixed">
		<h1><?php echo $feed->getTitle(); ?></h1>
                <a data-direction="reverse" data-iconpos="notext" data-icon="arrow-l" href="<?php echo $backUrl; ?>" ></a>
                <a data-icon="refresh" href="<?php echo $toggleUrl; ?>" ><?php echo $toggleText; ?></a>
	</div>

	<div data-role="content">

		<ul data-role="listview">

			<?php
	
			foreach ($entries as $entry) {
				
				echo '
					<li><a href="/m/entry/load/'.$entry->getId().'"'.($entry->getRead() == 1 ? ' class="read"' : '').'>'.$entry->getTitle().'</a></li>';
			}

			?>

		</ul>

	</div>

</div>
