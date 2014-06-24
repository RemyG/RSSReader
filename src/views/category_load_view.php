<?php
	$catDisplay = true;
	echo '<div class="category-title">
			<div class="title">'.$category->getName().'</div>
			<div class="meta">
				<a id="previous-entry-link" class="entry-navigation-link" href="" data-id="" title="Go to the previous entry"><i class="fa fa-caret-up"> </i></a>
				<a id="next-entry-link" class="entry-navigation-link" href="" data-id="" title="Go to the next entry"><i class="fa fa-caret-down"> </i></a>
				<a href="#" title="Refresh" data-id="'.$category->getId().'" class="category-refresh"><i class="fa fa-refresh"> </i></a>
				<span class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-check"> </i></a>
					<ul class="dropdown-menu" role="menu" aria-lebelledby="dLabel">
						<li><a href="#" data-id="'.$category->getId().'" class="category-markread" title="Mark all items as read">Mark all as read</a></li>
						<li class="divider"></li>
						<li><a href="#" data-id="'.$category->getId().'" class="category-marknotread" title="Mark all items as not read">Mark all as not read</a></li>
					</ul>
				</span>
			    <span class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"> </i></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
						<li><a href="#" data-id="'.$category->getId().'" class="category-show-all">Show all</a></li>
						<li style="display: none;"><a href="#" data-id="'.$category->getId().'" class="category-show-unread">Show unread</a></li>
					</ul>
				</span>
			</div>
		</div>';
	include 'templates/tpl_entries.php';
?>