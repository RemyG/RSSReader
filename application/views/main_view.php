<div id="feed-content">

	<?php 
	if (!isset($categoriesTree) || sizeof($categoriesTree) == 0)
	{
		echo "You haven't imported any feed yet. To do so, <a href='".BASE_URL."feed/add'><i class='icon-plus-sign'> </i> Import a new feed</a>
		or <a href='".BASE_URL."feed/importopml'><i class='icon-download'> </i> Import an OPML file</a>";
	}
	?>

</div>

<script type="text/javascript">

// Show / hide the feeds from a category
$(".nav-list").on("click", ".nav-header", function(e) {
	e.preventDefault();
	var catId = $(this).attr('data-cat-id');
	$('.load-feed-link[data-cat-id="'+catId+'"]').toggle();
});

// Open a feed
$(".load-feed-link").on("click", "a", function(e) {
	e.preventDefault();
	$('#overlay-content').show();
	var href = this.href;
	var $field = $(this).parent();
	$(this).blur();
	var request = $.ajax({
		url: href,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$('.load-feed-link').removeClass('active');
		$field.addClass('active');
		$("#feed-content").html(msg);
		scrollToActiveEntry('feed-content');
		$('#overlay-content').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay-content').hide();
		alert("Request failed: " + textStatus);
	});
});

// Open an entry
$("#feed-content").on("click", ".load-entry-link", function(e) {
	e.preventDefault();	
	var id = $(this).attr('data-id');
	var $field = $(this);

	if ($field.parent().hasClass('active')) {
		$("#load-entry-div-" + id).hide();
		$field.parent().removeClass('active')
	} else {
		var request = $.ajax({
			url: 'entry/load/' + id,
			type: "GET",
			dataType: "html"
		});
		request.done(function(msg) {
			$field.parent().addClass('read');
			$('.load-entry-div').hide();
			$('.load-entry-link').parent().removeClass('active');
			$field.parent().addClass('active');
			$("#load-entry-div-" + id).find('.entry-content').html(msg);		
			$("#load-entry-div-" + id).show();
			scrollToActiveEntry("load-entry-link-" + id);
			updateCountForEntry(id);		
		});
		request.fail(function(jqXHR, textStatus) {
			alert("Request failed: " + textStatus);
		});
	}
	
});

// Show an entry inside an iframe
$("#feed-content").on("click", ".iframe-link", function(e) {

	e.preventDefault();
	var id = $(this).attr('data-id');
	markEntryRead(id);
	$(this).hide();
	$("#load-entry-div-" + id).find('.source-link').show();	
	$("#load-entry-div-" + id).find('.entry-content').css('padding', 0);
	$("#load-entry-div-" + id).find('.entry-content').html('<iframe src="' + this.href + '" width="100%" height="500px" '
		+ 'sandbox="allow-same-origin" ></iframe>');
	var containerHeight = $(window).height(),
		metaHeight = $("#load-entry-link-" + id).outerHeight() + $("#load-entry-div-" + id).find('.entry-menu').outerHeight() + 75;
	$("#load-entry-div-" + id).find('iframe').height(containerHeight - metaHeight);
	scrollToActiveEntry("load-entry-link-" + id);
});

// Show the content of an entry from the source
$("#feed-content").on("click", ".source-link", function(e) {

	e.preventDefault();
	var id = $(this).attr('data-id');
	var $field = $(this);
	var request = $.ajax({
		url: 'entry/load/' + id,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$field.hide();
		markEntryRead(id);
		$("#load-entry-div-" + id).find('.iframe-link').show();
		$("#load-entry-div-" + id).find('.entry-content').css('padding', 5);
		$("#load-entry-div-" + id).find('.entry-content').html(msg);
		scrollToActiveEntry("load-entry-link-" + id);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});

});

// Mark an entry as read
$("#feed-content").on("click", ".read-link", function(e) {

	e.preventDefault();
	var id = $(this).attr('data-id');
	var $field = $(this);
	var request = $.ajax({
		url: 'entry/markread/' + id,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$("#load-entry-link-" + id).parent().addClass('read');
		$field.siblings('.unread-link').show();
		$field.hide();
		updateCountForEntry(id);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});

});

// Mark an entry as not read
$("#feed-content").on("click", ".unread-link", function(e) {

	e.preventDefault();
	var id = $(this).attr('data-id');
	var $field = $(this);
	var request = $.ajax({
		url: 'entry/markunread/' + id,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$("#load-entry-link-" + id).parent().removeClass('read');
		$field.siblings('.read-link').show();
		$field.hide();
		updateCountForEntry(id);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});

});

// Update a feed
$("#feed-content").on("click", ".feed-update", function(e) {

	e.preventDefault();
	$('#overlay').show();
	$('#overlay').find('.ajax-loader-text').text("Updating the feed");
	var id = $(this).attr('data-id');
	var href = this.href;
	var request = $.ajax({
		url: href,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$("#feed-content").html(msg);
		updateCountForFeed(id);
		$('#overlay').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay').hide();
		alert("Request failed: " + textStatus);
	});

});

// Remove an entry
$("#feed-content").on("click", ".remove-entry", function(e) {

	e.preventDefault();
	var id = $(this).attr('data-id');
	$('#entry-container-'+id).hide();
	if ($('#entry-container-'+id).prevUntil(".entries-date", ":visible").length == 0
		&& $('#entry-container-'+id).nextUntil(".entries-date", ":visible").length == 0)
	{
		$('#entry-container-'+id).prevAll(".entries-date:first").hide();
	}	
	var request = $.ajax({
		url: "entry/markread/" + id,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		updateCountForEntry(id);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});

});

// Update all feeds
$('.link-update-all').click(function(e) {
	e.preventDefault();
	$('#overlay').show();
	$('#overlay').find('.ajax-loader-text').text("Updating all feeds");
	var request = $.ajax({
		url: 'feed/updateall',
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$("#feed-content").html('');
		$("list-feeds").html(msg);
		$('#overlay').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay').hide();
		alert("Request failed: " + textStatus);
	});
});

// Mark all entries from a feed as read
$("#feed-content").on("click", ".feed-markread", function(e) {

	e.preventDefault();
	var id = $(this).attr('data-id');
	var request = $.ajax({
		url: 'feed/markread/'+id,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$("#feed-content").html(msg);
		updateCountForFeed(id);
		$('#overlay').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay').hide();
		alert("Request failed: " + textStatus);
	});

});

// Show the modal to Delete a feed (and its entries)
$("#feed-content").on("click", ".delete-feed", function(e) {
	e.preventDefault();	
	$('#modal-from-dom').modal('show');
});

// Close the feed deletion modal
$("#feed-content").on("click", ".cancel-delete", function(e) {
	e.preventDefault();
	$("#modal-from-dom").modal("hide");
});

// Delete a feed (and its entries)
$("#feed-content").on("click", ".confirm-delete", function(e) {
	e.preventDefault();
	var id = $(this).data("id");
	$("#load-feed-link-"+id).hide();	
	$("#modal-from-dom").modal("hide");
	$("#feed-content").html("");
	var request = $.ajax({
		url: 'feed/delete/' + id,
		type: "GET",
		dataType: "html"
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
});

// Show all entries from a feed (read and unread)
$("#feed-content").on("click", ".show-all", function(e) {
	e.preventDefault();
	var id = $(this).data("id");
	var request = $.ajax({
		url: 'feed/load/' + id + '/1',
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$("#feed-content").html(msg);
		$('.show-unread').parent().show();
		$('.show-all').parent().hide();
		$('#overlay').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
});

// Show only the unread entries from a feed
$("#feed-content").on("click", ".show-unread", function(e) {
	e.preventDefault();
	var id = $(this).data("id");
	var request = $.ajax({
		url: 'feed/load/' + id + '/0',
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$("#feed-content").html(msg);
		$('.show-unread').parent().hide();
		$('.show-all').parent().show();
		$('#overlay').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
});


function updateCountForEntry(id)
{
	var request2 = $.ajax({
		url: 'entry/count/' + id,
		type: "GET",
		dataType: "html"
	});
	request2.done(function(msg) {
		$('.load-feed-link.active').find('.feed-count').html(msg);
	});
}

function updateCountForFeed(id)
{
	var request2 = $.ajax({
		url: 'feed/count/' + id,
		type: "GET",
		dataType: "html"
	});
	request2.done(function(msg) {
		$('.load-feed-link.active').find('.feed-count').html(msg);
	});
}

function scrollToActiveEntry(entryId)
{
	var container = $("#content"),
		scrollTo = $("#" + entryId);
	container.scrollTop(
		scrollTo.offset().top - container.offset().top + container.scrollTop() - 10
	);
}

function markEntryRead(id)
{
	var request = $.ajax({
		url: 'entry/markread/' + id,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$("#load-entry-link-" + id).parent().addClass('read');
		$("#load-entry-div-" + id).find('.unread-link').show();
		$("#load-entry-div-" + id).find('.read-link').hide();
		updateCountForEntry(id);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
	
}

</script>