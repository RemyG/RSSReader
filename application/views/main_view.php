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

// rss or www
var viewType = 'rss';

var feedId = 0;

// Click on button Refresh
$('#header').on('click', '#button-refresh', function(e) {
	e.preventDefault();
	var href = $(this).data('href');
	$('#overlay').show();
	$('#overlay').find('.ajax-loader-text').text("Updating");
	var id = $(this).attr('data-id');
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

// Show / hide the feeds from a category
$("#feed-list").on("click", "li.category div i", function(e) {
	e.preventDefault();
	e.stopPropagation();
	$(this).parents('li.category').find('ul.feeds').toggle();
	$(this).toggleClass('icon-expand-alt icon-collapse-alt');
});

// Open a category
$("#feed-list").on("click", "li.category div", function(e) {
	e.preventDefault();
	$('#overlay-content').show();
	var $field = $(this).parents('li.category');
	var $id = $(this).parents('li.category').data('cat-id');
	var request = $.ajax({
		url: 'category/load/' + $id,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$('.load-feed-link').removeClass('active');
		$('li.category').removeClass('active');
		$('li.load-feed-link').removeClass('active');
		$field.addClass('active');
		$("#feed-content").html(msg);
		scrollToActiveEntry('feed-content');
		$('#entry-navigation-links').show();		
		$('#button-refresh').show();
		$('#button-refresh').attr('data-href', 'category/update/'+$id);
		$('#button-refresh').attr('data-id', $id);
		$('#overlay-content').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay-content').hide();
		alert("Request failed: " + textStatus);
	});
});

// Open a feed
$(".load-feed-link").on("click", "a", function(e) {
	e.preventDefault();
	var $field = $(this).parents('li.load-feed-link');
	viewType = $field.data('viewtype');
	$feedId = $field.data('id');
	$('#overlay-content').show();
	var href = this.href;	
	$(this).blur();
	var request = $.ajax({
		url: href,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$('.load-feed-link').removeClass('active');
		$('li.category').removeClass('active');
		$('li.load-feed-link').removeClass('active');
		$field.addClass('active');
		$("#feed-content").html(msg);
		scrollToActiveEntry('feed-content');
		$('#entry-navigation-links').show();
		$('#button-refresh').show();
		$('#button-refresh').attr('data-href', 'feed/update/'+$feedId);
		$('#button-refresh').attr('data-id', $feedId);
		$('#overlay-content').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay-content').hide();
		alert("Request failed: " + textStatus);
	});
});

// Open links in new tabs
$('#feed-content').on('click', '.entry-content a', function(e) {
	$(this).attr('target','_blank');
});

// Open an entry
$("#feed-content").on("click", ".load-entry-link", function(e) {
	e.preventDefault();	
	var id = $(this).attr('data-id');
	var href = $(this).data('href');
	var $field = $(this);
	if ($field.parent().hasClass('active')) {
		$("#load-entry-div-" + id).hide();
		$field.parent().removeClass('active');
		$('#entry-meta-links').hide();
	} else {
		openEntry(id, href);
	}
});

function openEntry(id, href)
{
	var $field = $('#load-entry-link-' + id);
	$field.parent().addClass('read');
	$('.load-entry-div').hide();
	$('.load-entry-link').parent().removeClass('active');
	$field.parent().addClass('active');	
	$("#load-entry-div-" + id).show();
	if (viewType == 'www')
	{
		openEntryAsFrame(id, href);
	}
	else if (viewType == 'rss')
	{
		openEntryAsSource(id);
	}
	$('a.entry-meta-link').attr('data-id', id);
	$('a.entry-meta-link').attr('href', href);
	$('#read-link').hide();
	$('#unread-link').show();
	$('#entry-meta-links').show();
}

function openEntryAsFrame(id, href)
{	
	markEntryRead(id);
	$('#iframe-link').hide();
	$('#source-link').show();
	$("#load-entry-div-" + id).find('.entry-content').css('padding', 0);
	$("#load-entry-div-" + id).find('.entry-content').html('<iframe src="' + href + '" width="100%" height="500px" '
		+ 'sandbox="allow-same-origin" ></iframe>');
	// var containerHeight = $(window).height();
	var containerHeight = $('#content').height();
	// var metaHeight = $("#load-entry-link-" + id).outerHeight() + $("#load-entry-div-" + id).find('.entry-menu').outerHeight() + 75;
	var metaHeight = $("#load-entry-link-" + id).outerHeight() + $("#load-entry-div-" + id).find('.entry-menu').outerHeight() + 30;
	$("#load-entry-div-" + id).find('iframe').height(containerHeight - metaHeight);
	scrollToActiveEntry("load-entry-link-" + id);
}

function openEntryAsSource(id)
{	
	var request = $.ajax({
		url: 'entry/load/' + id,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		markEntryRead(id);
		$('#source-link').hide();
		$('#iframe-link').show();
		$("#load-entry-div-" + id).find('.entry-content').css('padding', 10);
		$("#load-entry-div-" + id).find('.entry-content').html(msg);
		scrollToActiveEntry("load-entry-link-" + id);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
}

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
		dataType: "json",
	});
	request2.done(function(data) {
		if (data.feed == 0)
		{
			$('.load-feed-link.active').addClass('empty');
		}
		else
		{
			$('.load-feed-link.active').removeClass('empty');
			$('.load-feed-link.active').find('.feed-count').html(data.feed);
		}
		$('.load-feed-link.active').prevAll('.nav-header:first').find('.category-count').html(data.category);
	});
}

function updateCountForFeed(id)
{
	var request2 = $.ajax({
		url: 'feed/count/' + id,
		type: "GET",
		dataType: "json"
	});
	request2.done(function(data) {
		if (data.feed == 0)
		{
			$('#load-feed-link-' + id).addClass('empty');
		}
		else
		{
			$('#load-feed-link-' + id).removeClass('empty');
			$('#load-feed-link-' + id).find('.feed-count').html(data.feed);
		}
		$('#load-feed-link-' + id).prevAll('.nav-header:first').find('.category-count').html(data.category);
	});
}

function updateCountForCategory(id)
{

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
		$('#unread-link').show();
		$('#read-link').hide();
		updateCountForEntry(id);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});	
}

function setFeedViewInFrame()
{
	$('#load-feed-link-' + feedId).data('viewtype', 'www');
	var request = $.ajax({
		url: 'feed/setviewframe/' + feedId,
		type: "GET",
		dataType: "html"
	});
}

function setFeedViewAsSource()
{
	$('#load-feed-link-' + feedId).data('viewtype', 'rss');
	var request = $.ajax({
		url: 'feed/setviewsource/' + feedId,
		type: "GET",
		dataType: "html"
	});
}

setInterval(function() {
  //your jQuery ajax code
  $(".load-feed-link").each(function () {
  	var id = $(this).data('id');
  	updateCountForFeed(id);
  });
}, 1000 * 60 * 30); // where X is your every X minutes

$(document.documentElement).keyup(function (event) {
	event.preventDefault();
	// handle cursor keys
	if (event.keyCode == 80) { // P

		openPreviousEntry();

	} else if (event.keyCode == 78) { // N

		openNextEntry();
	}
});

function openPreviousEntry()
{
	var $currentEntryLinkContainer = $('.entry-link-container.active');
	if ($currentEntryLinkContainer.length > 0)
	{
		var $currentEntryContainer = $currentEntryLinkContainer.parent();    	
		var $nextEntryContainer = $currentEntryContainer.prevAll(".entry-container:first");
	}
	else
	{
		var $nextEntryContainer = $(".entry-container").first();
	}
	if ($nextEntryContainer.length > 0)
	{
		$nextEntryContainer.find('.load-entry-link').click();
	}
}

function openNextEntry()
{
	var $currentEntryLinkContainer = $('.entry-link-container.active');
	if ($currentEntryLinkContainer.length > 0)
	{
		var $currentEntryContainer = $currentEntryLinkContainer.parent();    	
		var $nextEntryContainer = $currentEntryContainer.nextAll(".entry-container:first");
	}
	else
	{
		var $nextEntryContainer = $(".entry-container").first();
	}
	if ($nextEntryContainer.length > 0)
	{
		$nextEntryContainer.find('.load-entry-link').click();
	}
}

</script>