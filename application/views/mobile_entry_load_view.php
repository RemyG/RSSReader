<div data-role="page">

	<div id="header" data-role="header" data-position="fixed">
		<h1><?php echo $entry->getTitle();?></h1>
		<a data-direction="reverse" data-iconpos="notext" data-icon="arrow-l" href="<?php echo $backUrl; ?>" ></a>
		<a href="" id="toggle-read" data-entry-id="<?php echo $entryId; ?>" ><i class="icon-check-empty"> </i></a>
	</div>

	<div data-role="content">
		<?php echo $entry->getContent();?>
	</div>

</div>

<script>
function markEntryRead(id)
{
	var request = $.ajax({
		url: '/m/entry/markread/' + id,
		type: "GET"
	});
	request.done(function(data) {
		$('#toggle-read').find("i").addClass("icon-check-empty");
		$('#toggle-read').find("i").removeClass("icon-check");
	});
}

function markEntryNotRead(id)
{
	var request = $.ajax({
		url: '/m/entry/markunread/' + id,
		type: "GET"
	});
	request.done(function(data) {
		$('#toggle-read').find("i").addClass("icon-check");
		$('#toggle-read').find("i").removeClass("icon-check-empty");
	});
}

$(document).ready(function() {
	//Mark an entry as read
	$("#toggle-read").on('click', function(e) {
		e.preventDefault();
		var id = $(this).attr('data-entry-id');
		if ($(this).find("i").hasClass("icon-check"))
		{
			markEntryRead(id);
		}
		else
		{
			markEntryNotRead(id);
		}
	});
});
</script>