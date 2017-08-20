<div class="category-title">
    <?php
    $currentDate = date('Y-m-d');
    ?>
    <div class="title">
	<a class="load-date" href="" data-date="<?php echo $prev_date; ?>"><i class="fa fa-arrow-circle-o-left"> </i></a>
	<?php echo $date; ?>
	<?php
	if ($date == $currentDate) {
		//echo '<i class="fa fa-arrow-circle-o-right"> </i>';
	} else {
		echo '<a class="load-date" href="" data-date="'.$next_date.'"><i class="fa fa-arrow-circle-o-right"> </i></a>';
	}
	?>
    </div>
    <div class="meta">
		<a id="previous-entry-link" class="entry-navigation-link" href="" data-id="" title="Go to the previous entry"><i class="fa fa-caret-up"> </i></a>
		<a id="next-entry-link" class="entry-navigation-link" href="" data-id="" title="Go to the next entry"><i class="fa fa-caret-down"> </i></a>
		<span class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-check"> </i></a>
			<ul class="dropdown-menu" role="menu" aria-lebelledby="dLabel">
				<li><a href="#" data-id="<?php echo $date; ?>" class="date-markread" title="Mark all items as read">Mark all as read</a></li>
				<li class="divider"></li>
				<li><a href="#" data-id="<?php echo $date; ?>" class="date-marknotread" title="Mark all items as not read">Mark all as not read</a></li>
			</ul>
		</span>
    </div>
</div>

<?php
$catDisplay = true;
include 'templates/tpl_entries.php';
?>