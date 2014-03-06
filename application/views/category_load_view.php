<?php
	$catDisplay = true;
	echo '<div class="category-title">
			<div class="title">'.$category->getName().'</div>
			<div class="meta">
				<a href="#" title="Refresh" data-id="'.$category->getId().'" class="category-refresh"><i class="icon-refresh"> </i></a>
			</div>
		</div>';
	include 'tpl_entries.php';
?>