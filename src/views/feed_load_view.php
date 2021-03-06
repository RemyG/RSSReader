<?php
	echo '
		<div class="feed-title">
			<div class="title"><a href="'.$feed->getBaseLink().'" target="_blank">'.$feed->getTitle().'</a></div>
			<div class="meta">
				<a id="previous-entry-link" class="entry-navigation-link" href="" data-id="" title="Go to the previous entry"><i class="fa fa-caret-up"> </i></a>
				<a id="next-entry-link" class="entry-navigation-link" href="" data-id="" title="Go to the next entry"><i class="fa fa-caret-down"> </i></a>
				<a href="#" title="Refresh" data-id="'.$feed->getId().'" class="feed-refresh"><i class="fa fa-refresh"> </i></a>
				<span class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-check"> </i></a>
					<ul class="dropdown-menu" role="menu" aria-lebelledby="dLabel">
						<li><a href="#" data-id="'.$feed->getId().'" class="feed-markread" title="Mark all items as read">Mark all as read</a></li>
						<li class="divider"></li>
						<li><a href="#" data-id="'.$feed->getId().'" class="feed-marknotread" title="Mark all items as not read">Mark all as not read</a></li>
					</ul>
				</span>
			    <span class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"> </i></a>
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

	include 'templates/tpl_slider_edit_feed.php';

	echo '<div id="slider-delete-feed" class="slider">
			<div class="slider-header">
				<a href="#" class="close cancel-delete-feed">&times;</a>
				<h3 class="title">Delete Feed</h3>
			</div>
			<div class="slider-body">
				<p>You are about to delete this feed, this procedure is irreversible.</p>
				<p>Do you want to proceed?</p>
			</div>
			<div class="slider-footer">
				<a href="#" data-id="'.$feed->getId().'" class="btn danger confirm-delete-feed">Yes</a>
				<a href="#" class="btn secondary cancel-delete-feed">No</a>
			</div>
		</div>';

	include 'templates/tpl_entries.php';
?>