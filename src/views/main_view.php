<div id="content">

<div id="feed-content">

	<?php
	if (!isset($categoriesTree) || sizeof($categoriesTree) == 0)
	{
		echo "You haven't imported any feed yet. To do so, <a href='".BASE_URL."feed/add'><i class='fa fa-plus-circle'> </i> Import a new feed</a>
		or <a href='".BASE_URL."feed/importopml'><i class='fa fa-download'> </i> Import an OPML file</a>";
	}
	?>

</div>

</div>

	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/main.js"></script>

	<script type="text/javascript">
		//$('#button-refresh').parent('li').show();
	</script>

	<script type="text/javascript">
		$(function() {
			$("ul.feeds").sortable({
				items: "li.load-feed-link",
				connectWith: "ul.feeds",
				placeholder: "feed-link-placeholder",
				delay: 150,
				update: function(event, ui)
				{
					var feedId = ui.item.data('id');
					var catId = ui.item.parents("li.category").data('cat-id');
					var order = ui.item.prevAll("li.load-feed-link").size();
					setNewOrder(feedId, catId, order);
				}
			});
		});
		function setNewOrder(feedId, catId, order)
		{
			var request = $.ajax({
				url: "feed/order/" + feedId + "/" + catId + "/" + order,
				type: "GET",
				dataType: "html"
			});
			request.done(function(msg) {
			});
			request.fail(function(jqXHR, textStatus) {
			});
		}
	</script>
