<?php
	echo '<p>'.$entry->getDescription().'</p>';
	echo '<p>'.$entry->getContent().'</p>';
	echo '<a href="'.$entry->getLink().'" target="_blank">&raquo;</a>';
	echo '<a class="iframe-link" data-id="'.$entry->getId().'" href="'.$entry->getLink().'">Open site;</a>';
?>