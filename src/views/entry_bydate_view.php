<div class="category-title">
    <?php
    $currentDate = date('Y-m-d');
    ?>
    <div class="title">
	<a class="load-date" href="" data-date="<?php echo $prev_date; ?>"><i class="fa fa-arrow-circle-o-left"> </i></a>
	<?php echo $date; ?>
	<?php
	if ($date == $currentDate) {
		echo '<i class="fa fa-arrow-circle-o-right"> </i>';
	} else {
		echo '<a class="load-date" href="" data-date="'.$next_date.'"><i class="fa fa-arrow-circle-o-right"> </i></a>';
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