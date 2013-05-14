<?php
	echo '
		<div class="meta">
			'.$entry->getUpdated('Y-m-d, H:i').' on
			<a href="'.($entry->getFeed()->getBaseLink() != null ? $entry->getFeed()->getBaseLink() : $entry->getFeed()->getLink()).'">'
				.$entry->getFeed()->getTitle().'</a>'
			.($entry->getAuthor() != null ? ' by '.$entry->getAuthor() : '').'
		</div>
		<p>'.$entry->getDescription().'</p>
		<p>'.$entry->getContent().'</p>';
?>