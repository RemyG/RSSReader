<?php
	echo '
		<div class="feed-title">
			<div class="title"><a href="'.$feed->getBaseLink().'">'.$feed->getTitle().'</a></div>
			<div class="meta">
				<a id="previous-entry-link" class="entry-navigation-link" href="" data-id="" title="Go to the previous entry"><i class="icon-caret-up"> </i></a>
				<a id="next-entry-link" class="entry-navigation-link" href="" data-id="" title="Go to the next entry"><i class="icon-caret-down"> </i></a>
				<a href="#" title="Refresh" data-id="'.$feed->getId().'" class="feed-refresh"><i class="icon-refresh"> </i></a>
				<span class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-check"> </i></a>
					<ul class="dropdown-menu" role="menu" aria-lebelledby="dLabel">
						<li><a href="#" data-id="'.$feed->getId().'" class="feed-markread" title="Mark all items as read">Mark all as read</a></li>
						<li class="divider"></li>
						<li><a href="#" data-id="'.$feed->getId().'" class="feed-marknotread" title="Mark all items as not read">Mark all as not read</a></li>
					</ul>
				</span>
			    <span class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-cog"> </i></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
						<li><a href="#" data-id="'.$feed->getId().'" class="show-all">Show all</a></li>
						<li style="display: none;"><a href="#" data-id="'.$feed->getId().'" class="show-unread">Show unread</a></li>
						<li class="divider"></li>
						<li><a href="feed/edit/'.$feed->getId().'" data-id="'.$feed->getId().'" class="feed-edit" title="Edit the feed">Edit feed</a></li>
						<li class="divider"></li>				
						<li><a href="#" data-id="'.$feed->getId().'" class="delete-feed">Delete feed</a></li>
					</ul>
				</span>
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
			<div class="entry-container" id="entry-container-'.$entry->getId().'">
				<div class="entry-link-container'.($entry->getRead() == 1 ? ' read' : '').'">
					<div class="toggle-read">
						<a href="#" class="mark-read" data-id="'.$entry->getId().'" title="Mark read">
							<i class="icon-check"> </i>
						</a>
						<a href="#" class="mark-unread" data-id="'.$entry->getId().'" title="Mark unread">
							<i class="icon-check-empty"> </i>
						</a>
					</div>'.
// 					<div class="remove-entry" data-id="'.$entry->getId().'">
// 						<a href="#" data-id="'.$entry->getId().'" title="Remove this entry">
// 							<i class="icon-remove-sign"> </i>
// 						</a>
// 					</div>
					'<div 	id="load-entry-link-'.$entry->getId().'" 
							class="load-entry-link" 
							data-id="'.$entry->getId().'" 
							data-href="'.$entry->getLink().'"
							data-feed-id="'.$entry->getFeed()->getId().'"
							data-viewtype="'.($entry->getFeed()->getViewFrame() == 0 ? 'rss' : 'www').'">
						<div class="title-wrapper">
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