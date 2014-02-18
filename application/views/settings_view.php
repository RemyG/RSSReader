<div id="content">

<h1>Settings</h1>

<h2>Categories</h2>

<ul id="category-list" class="nav nav-pills nav-stacked">
<?php
	foreach ($categories as $category)
	{
		echo '<li class="ui-state-default" data-id="'.$category->getId().'">'.$category->getName().'</li>';
	}
?>
</ul>

<h3>New category</h3>

<form method="post" id="form-create-category" class="settings-form">
	<label for="category-name">Category name</label>
	<input type="text" name="category-name" id="category-name" required />
	<input type="hidden" name="action" value="new-category" />
	<a href="#" class="btn btn-primary create-category">Create category</a>
</form>

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

$("form#form-create-category").on("click", "a.create-category", function(e) {
	e.preventDefault();
	var name = $("form#form-create-category").find("input#category-name").val();
	var request = $.ajax({
		url: "category/create",
		type: "POST",
		dataType: "json",
		data: { categoryName: name }
	});
	request.done(function(data) {
		var template = $('<li class="ui-state-default" data-id=""></li>');
		template.attr('data-id', data.id);
		template.html(data.name);
		$('ul#category-list').append(template);
	});
	request.fail(function(jqXHR, textStatus) {
	});
});

</script>