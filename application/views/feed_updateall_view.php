<?php
	$c = new Criteria();
	$c->add(EntryPeer::READ, 0);
	foreach ($feeds as $feed) {
		echo '<li class="load-feed-link" data-href="feed/load/'.$feed->getId().'">
				<div class="feed-title">'.$feed->getTitle().'</div>
				<div class="feed-count">'.$feed->countEntrys($c).'</div></li>';
	}
?>