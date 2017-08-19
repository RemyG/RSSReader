<div id="slider-delete-category" class="slider">

	<div class="slider-header">
		<a href="#" class="close cancel-delete-category">&times;</a>
		<h3 class="title">Delete Category</h3>
	</div>
	<div class="slider-body">
		<p>
			You are about to delete this category, with its associated feeds (<span
				id="totalFeeds"></span>) and entries (<span id="totalEntries"></span>).
			This procedure is irreversible.
		</p>
		<p>Do you want to proceed?</p>
	</div>
	<div class="slider-footer">
		<a href="#" data-id=""
			class="btn danger confirm-delete-category">Yes</a> <a href="#"
			class="btn secondary cancel-delete-category">No</a>
	</div>
</div>

<div id="content" class="content-center">

<h1>Settings</h1>

<h2>Categories</h2>

<ul id="category-list" class="nav nav-pills nav-stacked">
<?php
	foreach ($categories as $category)
	{
		echo '<li class="ui-state-default managed-cat" data-id="'.$category->getId().'">
        <i class="fa fa-arrows-v"> </i> '.$category->getName().'
	    <i class="fa fa-trash pull-right"> </i>
	    </li>';
	}
?>
</ul>

<h3>New category</h3>

<form method="post" id="form-create-category" class="settings-form form-block">
	<fieldset>
		<input type="hidden" name="action" value="new-category" />
		<div class="field-group">
			<label for="category-name">Category name</label>
			<input type="text" name="category-name" id="category-name" required />
		</div>
		<div class="field-group">
			<a href="#" class="btn btn-primary create-category">Create category</a>
		</div>
	</fieldset>
</form>

<h2>Export OPML</h2>

<a class="btn btn-default" href="settings/exportOpml" role="button">Export OPML</a>

<h2>Update user</h2>

<form method="post" class="settings-form form-block" novalidate>
	<fieldset>
		<input type="hidden" name="action" value="update-user" />
		<div class="field-group">
			<label for="login">Login</label>
			<input type="email" name="login" id="login" required />
		</div>
		<div class="field-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" required />
		</div>
		<div class="field-group">
			<button type="submit" class="btn">Update</button>
		</div>
	</fieldset>
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

$("#category-list").on("click", "i.fa-trash", function(e) {
	e.preventDefault();
	var catId = $(this).parents('li').attr('data-id');
	var request = $.ajax({
		url: "category/details/" + catId,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		$(".confirm-delete-category").attr('data-id', catId);
		$("#totalFeeds").html(data.totalFeeds);
		$("#totalEntries").html(data.totalEntries);
		$("#slider-delete-category").slideToggle();
	});
	request.fail(function(jqXHR, textStatus) {
	});
});

// Close the feed deletion modal
$("#slider-delete-category").on("click", ".cancel-delete-category", function(e) {
	e.preventDefault();
	$("#slider-delete-category").slideUp();
});

// Delete a feed (and its entries)
$("#slider-delete-category").on("click", ".confirm-delete-category", function(e) {
	e.preventDefault();
	$('#overlay').show();
	$('#overlay').find('.ajax-loader-text').text("Deleting this category.");
	var catId = $(this).attr("data-id");
	var request = $.ajax({
		url: 'category/delete/' + catId,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		if (data.result === 1)
		{
			$('li.managed-cat[data-id=' + catId + ']').remove();
			$("#slider-delete-category").slideUp();
		}
		else if (data.errors)
		{
			$.each(data.errors, function(i, item) {
				$("#slider-delete-category").find("div.errors").append('<div class="error-message">' + item + '</div>');
			});
		}
		$('#overlay').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay').hide();
		$("#slider-delete-feed").find("div.errors").append('<div class="error-message">' + textStatus + '</div>');
	});
});





$("form#form-create-category").find("input#category-name").keypress(function(e) {
    if(e.which == 13) {
        e.preventDefault();
		createCategory();
    }
});

$("form#form-create-category").on("click", "a.create-category", function(e) {
	e.preventDefault();
	createCategory();
});

function createCategory()
{
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
		template.html('<i class="fa fa-arrows-v"> </i> ' + data.name);
		$('ul#category-list').append(template);
		$("form#form-create-category").find("input#category-name").val('');
	});
	request.fail(function(jqXHR, textStatus) {
	});
}

</script>