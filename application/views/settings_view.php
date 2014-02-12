<div id="content">

<h1>Settings</h1>

<h2>Categories</h2>

<div class="container-fluid">

	<div class="row-fluid">

		<div class="span6">

			<ul id="category-list" class="nav nav-pills nav-stacked">
				<?php
					foreach ($categories as $category)
					{
						echo '<li class="ui-state-default" data-id="'.$category->getId().'"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'.$category->getName().'</li>';
					}
				?>
			</ul>

		</div>

		<div class="span6">

			<h3>New category</h3>

			<form method="post" class="settings-form">
				<label for="category-name">Category name</label>
				<input type="text" name="category-text" id="category-text" required />
				<input type="hidden" name="action" value="new-category" />
				<button type="submit" class="btn">Create category</button>
			</form>

		</div>

	</div>

</div>

<h2>Update user</h2>

<form method="post" class="settings-form" novalidate>
	<label for="login">Login</label>
	<input type="email" name="login" id="login" required />
	<label for="password">Password</label>
	<input type="password" name="password" id="password" required />
	<input type="hidden" name="action" value="update-user" />
	<button type="submit" class="btn">Update</button>
</form>

</div>

<script type="text/javascript">
$(function() {
	$( "#category-list" ).sortable({
		update: function(event, ui)
		{
			var catId = ui.item.data('id');
			var order = ui.item.prevAll("li").size();
			setNewCatOrder(catId, order);
		}
	});
});
function setNewCatOrder(catId, order)
{
	var request = $.ajax({
		url: "category/order/" + catId + "/" + order,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
	});
	request.fail(function(jqXHR, textStatus) {
	});
}
$("#category-list").on("click", "a", function(e) {
	e.preventDefault();
});
</script>