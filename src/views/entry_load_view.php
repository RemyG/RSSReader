<?php

function sanitize_output($buffer) {

	$search = array(
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space
			'/(\s)+/s'       // shorten multiple whitespace sequences
	);

	$replace = array(
			'>',
			'<',
			'\\1'
	);

	$buffer = preg_replace($search, $replace, $buffer);

	return $buffer;
}

	echo sanitize_output('
		<div class="inner-title"><a href="'.$entry->getLink().'">'.$entry->getTitle().'</a></div>
		<div class="meta">
			'.$entry->getUpdated('Y-m-d, H:i').' on
			 <a href="'.($entry->getFeed()->getBaseLink() != null ? $entry->getFeed()->getBaseLink() : $entry->getFeed()->getLink()).'">'
				.$entry->getFeed()->getTitle().'</a>'
			.($entry->getAuthor() != null ? ' by '.$entry->getAuthor() : '').' 
			- <a class="iframe-link" data-entry-id="'.$entry->getId().'" data-feed-id="'.$entry->getFeed()->getId().'" href="'.$entry->getLink().'" title="View as website">View as website</a>
			<a class="source-link" data-entry-id="'.$entry->getId().'" data-feed-id="'.$entry->getFeed()->getId().'" href="'.$entry->getLink().'" title="View RSS feed">View as feed</a>
		</div>
		<div class="entry-content-text">
			<p>'.$entry->getDescription().'</p>
			<p>'.$entry->getContent().'</p>
		</div>');
?>