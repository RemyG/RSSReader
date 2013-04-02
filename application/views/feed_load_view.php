<?php
	//var_dump($feeds);
	echo '<div class="feed-title">
			<div class="title">'.$feed->getTitle().'</div>
			<div class="meta">
				<a href="feed/markread/'.$feed->getId().'" data-id="'.$feed->getId().'" class="feed-markread" title="Mark all items read"><i class="icon-check"> </i></a>
				<a href="feed/update/'.$feed->getId().'" data-id="'.$feed->getId().'" class="feed-update" title="Update feed"><i class="icon-refresh"> </i></a>
			</div>
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
				<div class="entry-link-container">
					<div class="remove">
						<a href="#" class="remove-entry" data-id="'.$entry->getId().'" title="Remove this entry">
							<i class="icon-remove-sign"> </i>
						</a>
					</div>
					<div id="load-entry-link-'.$entry->getId().'" class="load-entry-link" data-id="'.$entry->getId().'">
						<div class="title">'.$entry->getTitle().'</div>						
						<div class="date">'.$entry->getUpdated('Y-m-d').'</div>
					</div>
				</div>';
		echo '
				<div class="load-entry-div" id="load-entry-div-'.$entry->getId().'">
					<div class="entry-menu">
						<a class="iframe-link" data-id="'.$entry->getId().'" href="'.$entry->getLink().'" title="View as website">WWW</a>
						<a class="source-link" data-id="'.$entry->getId().'" href="'.$entry->getLink().'">RSS</a>
						<a href="'.$entry->getLink().'" target="_blank" title="Open website in a new tab"><i class="icon-forward"> </i></a>
						<a class="read-link" href="'.$entry->getLink().'" data-id="'.$entry->getId().'" title="Mark as read"><i class="icon-check"> </i></a>
						<a class="unread-link" href="'.$entry->getLink().'" data-id="'.$entry->getId().'" title="Mark as unread"><i class="icon-check-empty"> </i></a>
					</div>
					<div class="entry-content"></div>
				</div>';
	}
	echo '</div>';
?>