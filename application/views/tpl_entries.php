<?php

echo '<div class="list-entries">';
$today = new DateTime(date('Y-m-d'));
$currentDay = -1;
$olderThan2 = false;
foreach ($entries as $entry) {
	$entryAge = $today->diff(new DateTime($entry->getUpdated('Y-m-d')))->days;
	if ($entryAge != $currentDay)
	{
		$currentDay = $entryAge;
		switch ($entryAge) {
			case 0:
				echo '<div class="entries-date">Today</div>';
				break;
			case 1:
				echo '<div class="entries-date">Yesterday</div>';
				break;
			case 2:
				echo '<div class="entries-date">2 days ago</div>';
				break;
			default:
				if (!$olderThan2)
				{
					$olderThan2 = true;
					echo '<div class="entries-date">More than 2 days ago</div>';
				}
				break;
		}
	}
	include 'tpl_entry.php';
}
echo '</div>';