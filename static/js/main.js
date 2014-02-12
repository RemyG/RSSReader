/*** FUNCTIONS - FEEDS ***/

function loadFeed(feedId, feedHref)
{
	var href = 'feed/load/' + feedId;
	$('#overlay-content').show();
	var request = $.ajax({
		url: href,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayFeed(feedId, data.html, data.count, data.categorycount, data.valid);
		$('#overlay-content').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay-content').hide();
		alert("Request failed: " + textStatus);
	});
}

function updateFeed(feedId)
{
	var href = 'feed/update/' + feedId;
	$('#overlay').show();
	var request = $.ajax({
		url: href,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayFeed(feedId, data.html, data.count, data.categorycount, data.valid);
		$('#overlay').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay').hide();
		alert("Request failed: " + textStatus);
	});
}

function displayFeed(feedId, feedHtml, feedCount, categoryCount, valid)
{
	$('li.load-feed-link').removeClass('active');
	$('li.category').removeClass('active');
	$('#load-feed-link-' + feedId).addClass('active');
	if (valid == true)
	{
		$('#load-feed-link-' + feedId).removeClass('not-valid');
	}
	else
	{
		$('#load-feed-link-' + feedId).addClass('not-valid');
	}
	$("#feed-content").html(feedHtml);
	positionFeedEntries();
	setCountForFeed(feedId, feedCount, categoryCount);
	scrollToActiveEntry('feed-content');
	$('#entry-navigation-links').show();
	$('#button-refresh').show();
	$('#button-refresh').attr('data-href', 'feed/update/' + feedId);
	$('#button-refresh').attr('data-type', 'feed');
	$('#button-refresh').attr('data-id', feedId);
}

function updateCountForFeed(feedId)
{
	var request2 = $.ajax({
		url: 'feed/count/' + feedId,
		type: "GET",
		dataType: "json"
	});
	request2.done(function(data) {
		setCountForFeed(feedId, data.feed, data.category);
	});
}

function setCountForFeed(iFeedId, feedCount, catCount)
{
	if (feedCount == 0)
	{
		$('#load-feed-link-' + iFeedId).addClass('empty');
	}
	else
	{
		$('#load-feed-link-' + iFeedId).removeClass('empty');
		$('#load-feed-link-' + iFeedId).find('.feed-count').html(feedCount);
	}
	$('#load-feed-link-' + iFeedId).parents('li.category').find('.category-count').html(catCount);
}

/*** END - FUNCTIONS - FEEDS ***/

/*** FUNCTIONS - CATEGORIES ***/

function loadCategory(catId)
{
	$('#overlay-content').show();
	var request = $.ajax({
		url: 'category/load/' + catId,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayCategory(catId, data.html, data.count);
		$('#overlay-content').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay-content').hide();
		alert("Request failed: " + textStatus);
	});
}

function displayCategory(catId, catHtml, catCount)
{
	$('.load-feed-link').removeClass('active');
	$('li.category').removeClass('active');
	$('li.load-feed-link').removeClass('active');
	$('li.category[data-cat-id=' + catId + ']').addClass('active');
	$("#feed-content").html(catHtml);
	positionFeedEntries();
	setCountForCategory(catId, catCount);
	scrollToActiveEntry('feed-content');
	$('#entry-navigation-links').show();		
	$('#button-refresh').show();
	$('#button-refresh').attr('data-href', 'category/update/' + catId);
	$('#button-refresh').attr('data-type', 'category');
	$('#button-refresh').attr('data-id', catId);
}

function setCountForCategory(catId, catCount)
{
	$('li.category[data-cat-id=' + catId + ']').find('.category-count').html(catCount);
}

function updateCategory(catId)
{
	$('#overlay').show();
	var request = $.ajax({
		url: 'category/update/' + catId,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayCategory(catId, data.html, data.count);
		$('#overlay').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay').hide();
		alert("Request failed: " + textStatus);
	});
}

/*** END - FUNCTIONS - CATEGORIES ***/

function positionFeedEntries() {
	if ($("#feed-content").children(".feed-title").length)
	{
		$("#feed-content").find(".feed-title").offset({left: $("#feed-content").offset().left});
		$("#feed-content").find(".list-entries").offset({top: $("#feed-content").find(".feed-title").offset().top + $("#feed-content").find(".feed-title").outerHeight()});
		$("#feed-content").find(".list-entries").height($(window).height() - $("#feed-content").find(".list-entries").offset().top - 1);
	}
	else if ($("#feed-content").children(".category-title").length)
	{
		$("#feed-content").find(".category-title").offset({left: $("#feed-content").offset().left});
		$("#feed-content").find(".list-entries").offset({top: $("#feed-content").find(".category-title").offset().top + $("#feed-content").find(".category-title").outerHeight()});
		$("#feed-content").find(".list-entries").height($(window).height() - $("#feed-content").find(".list-entries").offset().top - 1);
	}
}

/*** FUNCTIONS - ALL ***/

function updateAll()
{

}

/*** END - FUNCTIONS - ALL ***/

//Show the content of an entry from the source
$("#feed-content").on("click", '.source-link', function(e) {
	e.preventDefault();
	viewType = 'rss';
	var id = $(this).attr('data-entry-id');
	var feedId = $(this).attr('data-feed-id');
	setFeedViewAsSource(feedId);
	openEntryAsSource(id);
});

// Show an entry inside an iframe
$("#feed-content").on("click", '.iframe-link', function(e) {
	e.preventDefault();
	viewType = 'www';
	var id = $(this).attr('data-entry-id');
	var feedId = $(this).attr('data-feed-id');
	setFeedViewInFrame(feedId);
	openEntryAsFrame(id, this.href);	
});

$("#feed-content").on("click", "a.feed-refresh", function(e) {
	e.preventDefault();
	var id = $(this).attr('data-id');
	updateFeed(id);
});

$("#feed-content").on("click", "a.category-refresh", function(e) {
	e.preventDefault();
	var id = $(this).attr('data-id');
	updateCategory(id);
});

// Click on button Refresh
$('#header').on('click', '#button-refresh', function(e) {
	e.preventDefault();
	var updateType = $(this).attr('data-type');
	var id = $(this).attr('data-id');
	if (updateType == 'feed')
	{
		updateFeed(id);
	}
	else if (updateType == 'category')
	{
		updateCategory(id);
	}
	else if (updateType == 'all')
	{
		updateAll();
	}
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
	var id = $(this).parents('li.category').data('cat-id');
	loadCategory(id);
});

// Open a feed
$("#feed-list").on("click", "li.load-feed-link", function(e) {
	e.preventDefault();
	var feedId = $(this).attr('data-id');
	var feedHref = $(this).attr('data-baselink');
	loadFeed(feedId, feedHref);
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
	$('.load-entry-link').parents('div.entry-container').removeClass('active');
	$field.parent().addClass('active');	
	$field.parents('div.entry-container').addClass('active');
	$("#load-entry-div-" + id).show();
	if ($field.attr('data-viewtype') == 'www')
	{
		openEntryAsFrame(id, href);
	}
	else if ($field.attr('data-viewtype') == 'rss')
	{
		openEntryAsSource(id);
	}
}

function openEntryAsFrame(id, href)
{	
	var request = $.ajax({
		url: 'entry/loadframe/' + id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		markEntryRead(id);
		$("#load-entry-div-" + id).find('.entry-content').addClass('frame');
		$("#load-entry-div-" + id).find('.entry-content').html(data.html);
		$("#load-entry-div-" + id).find('.entry-content-text').html('<iframe src="' + href + '" width="100%" height="500px" '
				+ 'sandbox="allow-same-origin" ></iframe>');
		var containerHeight = $('#content').height();
		var metaHeight = $("#load-entry-link-" + id).outerHeight() + $("#load-entry-div-" + id).find('.entry-menu').outerHeight() + 15;
		$("#load-entry-div-" + id).find('iframe').height(containerHeight - metaHeight);
		$('#feed-content').find('a.source-link').show();
		$('#feed-content').find('a.iframe-link').hide();
		scrollToActiveEntry("load-entry-link-" + id);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
}

function openEntryAsSource(id)
{
	var request = $.ajax({
		url: 'entry/load/' + id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		markEntryRead(id);
		$("#load-entry-div-" + id).find('.entry-content').removeClass('frame');
		$("#load-entry-div-" + id).find('.entry-content').html(data.html);
		$('#feed-content').find('a.source-link').hide();
		$('#feed-content').find('a.iframe-link').show();
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

// Edit a feed
$("#feed-content").on("click", "a.feed-edit", function(e) {
	e.preventDefault();
	var id = $(this).attr('data-id');
	var hrefEdit = $(this).attr('href');
	var request = $.ajax({
		url: hrefEdit,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		$('#modal-edit').find('input[name=feed-id]').val(data.feedid);
		$('#modal-edit').find('input[name=feed-title]').val(data.feedtitle);
		$('#modal-edit').find('input[name=feed-link]').val(data.feedlink);
		$('#modal-edit').find('input[name=feed-base-link]').val(data.feedbaselink);
		$(".modal-backdrop").show();
		$('#modal-edit').show();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay').hide();
		alert("Request failed: " + textStatus);
	});	
});

// Close the feed deletion modal
$("#modal-edit").on("click", ".cancel-edit-feed", function(e) {
	e.preventDefault();
	$("#modal-edit").hide();
	$(".modal-backdrop").hide();
});

// Delete a feed (and its entries)
$("#modal-edit").on("click", ".confirm-edit-feed", function(e) {
	e.preventDefault();
	var $id = $('a.feed-edit').data("id");
	$.post($('a.feed-edit').attr('href'), $("#form-edit").serialize(), function(data) {
			if (data.result == '1')
			{
				$('#load-feed-link-' + $id).find('span.feed-title').html(data.feedtitle);
				$("#modal-edit").hide();
				$(".modal-backdrop").hide();
				$('#button-refresh').click();
			}
		}, "json");
});

// Remove an entry
$("#feed-content").on("click", ".remove-entry", function(e) {

	e.preventDefault();
	var id = $(this).attr('data-id');
	if ($('#entry-container-'+id).prevUntil(".entries-date", ":visible").length == 0
			&& $('#entry-container-'+id).nextUntil(".entries-date", ":visible").length == 0)
	{
		$('#entry-container-'+id).prevAll(".entries-date:first").remove();
	}
	$('#entry-container-'+id).remove();
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
		dataType: "json"
	});
	request.done(function(data) {
		displayFeed(data.feedId, data.html, data.count, data.categorycount, data.valid);
		$('#overlay').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay').hide();
		alert("Request failed: " + textStatus);
	});

});

$("#feed-content").on("click", ".feed-marknotread", function(e) {

	e.preventDefault();
	var id = $(this).attr('data-id');
	var request = $.ajax({
		url: 'feed/marknotread/'+id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayFeed(data.feedId, data.html, data.count, data.categorycount, data.valid);
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
	var dataId = $(this).attr('data-id');
	$('#modal-from-dom').find('a.confirm-delete').attr('data-id', dataId);
	$(".modal-backdrop").show();
	$('#modal-from-dom').show();
});

// Close the feed deletion modal
$("#modal-from-dom").on("click", ".cancel-delete", function(e) {
	e.preventDefault();
	$("#modal-from-dom").hide();
	$(".modal-backdrop").hide();
});

// Delete a feed (and its entries)
$("#modal-from-dom").on("click", ".confirm-delete", function(e) {
	e.preventDefault();
	var id = $(this).data("id");
	$("#load-feed-link-"+id).hide();	
	$("#modal-from-dom").hide();
	$(".modal-backdrop").hide();
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
		dataType: "json"
	});
	request.done(function(data) {
		displayFeed(data.feedId, data.html, data.count, data.categorycount, data.valid);
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
		dataType: "json"
	});
	request.done(function(data) {
		displayFeed(data.feedId, data.html, data.count, data.categorycount, data.valid);
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

function updateCountForCategory(id)
{

}

function scrollToActiveEntry(entryId)
{
	var container = $('#feed-content').find(".list-entries"),
		scrollTo = $("#" + entryId);
	container.scrollTop(
		scrollTo.offset().top - container.offset().top + container.scrollTop()
	);
}

//Mark an entry as read
$("#feed-content").on('click', '.toggle-read a', function(e) {
	e.preventDefault();
	var id = $(this).attr('data-id');
	toggleEntryRead(id);
});

function toggleEntryRead(id)
{
	var read = $("#load-entry-link-" + id).parent().hasClass('read');
	if (read)
	{
		markEntryNotRead(id);
	}
	else
	{
		markEntryRead(id);
	}
	
}

function markEntryRead(id)
{
	var request = $.ajax({
		url: 'entry/markread/' + id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		$("#load-entry-link-" + id).parent().addClass('read');
		setCountForFeed(data.feedId, data.feedCount, data.categoryCount);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});	
}

function markEntryNotRead(id)
{
	var request = $.ajax({
		url: 'entry/markunread/' + id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		$("#load-entry-link-" + id).parent().removeClass('read');
		setCountForFeed(data.feedId, data.feedCount, data.categoryCount);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
}

function setFeedViewInFrame(id)
{
	$('#load-feed-link-' + id).attr('data-viewtype', 'www');
	$('.load-entry-link[data-feed-id=' + id + ']').attr('data-viewtype', 'www');
	var request = $.ajax({
		url: 'feed/setviewframe/' + id,
		type: "GET",
		dataType: "html"
	});
}

function setFeedViewAsSource(id)
{
	$('#load-feed-link-' + id).attr('data-viewtype', 'rss');
	$('.load-entry-link[data-feed-id=' + id + ']').attr('data-viewtype', 'rss');
	var request = $.ajax({
		url: 'feed/setviewsource/' + id,
		type: "GET",
		dataType: "html"
	});
}

setInterval(function() {
	//your jQuery ajax code
	var request = $.ajax({
		url: 'feed/countAll',
		type: "GET",
		dataType: "json"
	});
	request.done(function(data)
	{
		for(var i = 0 ; i < data.length ; i++)
		{
			var obj = data[i];
			setCountForFeed(obj['feedId'], obj['feedCount'], obj['categoryCount']);
		}
	});
}, 1000 * 60 * 10); // where X is your every X minutes

$("#feed-content").on("click", '#previous-entry-link', function(e) {
	e.preventDefault();
	openPreviousEntry();	
});

$("#feed-content").on("click", '#next-entry-link', function(e) {
	e.preventDefault();
	openNextEntry();	
});

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

// Add new feed

//Show the modal to Add a feed
$("#header").on("click", "#add-new-feed", function(e) {
	e.preventDefault();
	$(".modal-backdrop").show();
	$('#modal-new-feed').show();
});

// Close the feed deletion modal
$("#modal-new-feed").on("click", ".cancel-new-feed", function(e) {
	e.preventDefault();
	$("#modal-new-feed").hide();
	$(".modal-backdrop").hide();
});

// Delete a feed (and its entries)
$("#modal-new-feed").on("click", ".confirm-new-feed", function(e) {
	e.preventDefault();
	var form = $(this).closest("#modal-new-feed").find("form.feed-form");
	var feedUrlValue = form.find("#feed-url").val();
	var catIdValue = form.find("#feed-category").val();
	
	var request = $.ajax({
		url: 'feed/add',
		type: "POST",
		dataType: "json",
		data: {feedUrl: feedUrlValue, feedCategory: catIdValue}
	});
	request.done(function(data, textStatus, jqXHR) {
		addNewFeed(data.catId, data.feedId, data.feedName, data.feedCount, data.feedUrl);
		$("#modal-new-feed").hide();
		$(".modal-backdrop").hide();
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
});

function addNewFeed(catId, feedId, feedName, feedCount, feedUrl)
{
	var template = $("<li class='load-feed-link empty not-valid' id='load-feed-link-1' data-href='feed/load/1' data-cat-id='2' data-id='1' data-viewtype='rss' data-baselink='http://friendfeed.com/'>"
			+ "<a href='feed/load/1'><span class='feed-title'>Title</span><span class='feed-count'></span></a></li>");
	
	template.find('li').attr('id', 'load-feed-link-' + feedId);
	template.find('li').attr('data-href', 'feed/load/' + feedId);
	template.find('li').attr('data-cat-id', catId);
	template.find('li').attr('data-id', feedId);
	template.find('li').attr('data-baselink', feedUrl);
	template.find('a').attr('href', 'feed/load/' + feedId);
	template.find('span.feed-title').html(feedName);
	
	var cat = $('li.category[data-cat-id="' + catId + '"]').find("ul.feeds");
	
	cat.append(template);
}

//Bind to the resize event of the window object
$(window).on("resize", function () {
    // Set .right's width to the window width minus 480 pixels
	var height = $(window).height() - $('div#left-menu').offset().top;
    $('div#left-menu').height(height);
    $('div#left-menu-inner').height(height);
    $('div#feed-list-container').height(height);
    positionFeedEntries();
// Invoke the resize event immediately
}).resize();