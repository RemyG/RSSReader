<?php
	//var_dump($feeds);
	echo '<div class="feed-title">'.$feed->getTitle().'</div>';
	echo '<div class="list-entries">';
	foreach ($entries as $entry) {
		echo '<div class="load-entry-link" data-id="'.$entry->getId().'">'.$entry->getTitle().'</div>';
		echo '
			<div class="load-entry-div" id="load-entry-div-'.$entry->getId().'">
				<div class="entry-menu">
					<a class="iframe-link" data-id="'.$entry->getId().'" href="'.$entry->getLink().'">Open site</a>
					<a class="source-link" data-id="'.$entry->getId().'" href="'.$entry->getLink().'">View source</a>
				</div>
				<div class="entry-content"></div>
			</div>';
	}
	echo '</div>';
?>