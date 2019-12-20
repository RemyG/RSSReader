<div class="category-title">
<div class="title">
	<a class="load-latest" href="" data-page="<?php echo $current_page + 1; ?>"><i class="fa fa-arrow-circle-o-left"> </i></a>
	Latest entries
	<?php
	if ($current_page > 0) {
		echo '<a class="load-latest" href="" data-page="'.($current_page - 1).'"><i class="fa fa-arrow-circle-o-right"> </i></a>';
	}
	?>
    </div>
	<div class="meta">
		<a id="previous-entry-link" class="entry-navigation-link" href="" data-id="" title="Go to the previous entry"><i class="fa fa-caret-up"> </i></a>
		<a id="next-entry-link" class="entry-navigation-link" href="" data-id="" title="Go to the next entry"><i class="fa fa-caret-down"> </i></a>
	</div>
</div>

<?php
	$catDisplay = true;
	include 'templates/tpl_entries.php';
?>