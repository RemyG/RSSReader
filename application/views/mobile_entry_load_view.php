<div data-role="page">

	<div id="header" data-role="header" data-position="fixed">
		<h1><?php echo $entry->getTitle();?></h1>
		<a data-direction="reverse" data-iconpos="notext" data-icon="arrow-l" href="<?php echo $backUrl; ?>" ></a>
		<a href="" id="toggle-read" data-entry-id="<?php echo $entryId; ?>" ><i class="fa fa-square-o"> </i></a>
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
		$('#toggle-read').find("i").addClass("fa-square-o");
		$('#toggle-read').find("i").removeClass("fa-check-square");
	});
}

function markEntryNotRead(id)
{
	var request = $.ajax({
		url: '/m/entry/markunread/' + id,
		type: "GET"
	});
	request.done(function(data) {
		$('#toggle-read').find("i").addClass("fa-check-square");
		$('#toggle-read').find("i").removeClass("fa-square-o");
	});
}

$(document).ready(function() {
	//Mark an entry as read
	$("#toggle-read").on('click', function(e) {
		e.preventDefault();
		var id = $(this).attr('data-entry-id');
		if ($(this).find("i").hasClass("fa-check-square"))
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