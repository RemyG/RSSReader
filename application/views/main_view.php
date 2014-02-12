<div id="content">

<div id="feed-content">

	<?php 
	if (!isset($categoriesTree) || sizeof($categoriesTree) == 0)
	{
		echo "You haven't imported any feed yet. To do so, <a href='".BASE_URL."feed/add'><i class='icon-plus-sign'> </i> Import a new feed</a>
		or <a href='".BASE_URL."feed/importopml'><i class='icon-download'> </i> Import an OPML file</a>";
	}
	?>	

</div>

</div>

<div id="footer-menu">

	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/main.min.js"></script>
	
	<script type="text/javascript">
		//$('#button-refresh').parent('li').show();
	</script>
	
	<script type="text/javascript">
		$(function() {
			$("#feed-list").sortable({
				items: "li.load-feed-link",
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

</div>