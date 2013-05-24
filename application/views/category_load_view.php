<?php
	echo '<div class="category-title">
			<div class="title">'.$category->getName().'</div>
		</div>';
	echo '<div class="list-entries">';
	$today = new DateTime(date('Y-m-d'));
	$currentDay = -1;
	$olderThan2 = false;
	foreach ($entries as $entry) {
		$entryAge = $today->diff(new DateTime($entry->getUpdated('Y-m-d')))->days;
		if ($entryAge != $currentDay)
		{
			$currentDay = $entryAge;
			switch ($entryAge) {
				case 0:
					echo '<div class="entries-date">Today</div>';
					break;
				case 1:
					echo '<div class="entries-date">Yesterday</div>';
					break;
				case 2:
					echo '<div class="entries-date">2 days ago</div>';
					break;
				default:
					if (!$olderThan2)
					{
						$olderThan2 = true;
						echo '<div class="entries-date">More than 2 days ago</div>';
					}
					break;
			}
			
		}
		echo '
			<div class="entry-container" id="entry-container-'.$entry->getId().'">
				<div class="entry-link-container'.($entry->getRead() == 1 ? ' read' : '').'">
					<div class="remove-entry" data-id="'.$entry->getId().'">
						<a href="#" data-id="'.$entry->getId().'" title="Remove this entry">
							<i class="icon-remove-sign"> </i>
						</a>
					</div>
					<div 	id="load-entry-link-'.$entry->getId().'" 
							class="load-entry-link" 
							data-id="'.$entry->getId().'" 
							data-href="'.$entry->getLink().'"
							data-feed-id="'.$entry->getFeed()->getId().'"
							data-viewtype="'.($entry->getFeed()->getViewFrame() == 0 ? 'rss' : 'www').'">
						<div class="title-wrapper">
							<div class="feed-title">'.$entry->getFeed()->getTitle().'</div>
							<div class="title">'.$entry->getTitle().'</div>
						</div>
						<div class="date">'.$entry->getUpdated('Y-m-d').'</div>
					</div>
				</div>
				<div class="load-entry-div" id="load-entry-div-'.$entry->getId().'">
					<div class="entry-content"></div>
				</div>
			</div>';
	}
	echo '</div>';
?>