function displayOverlay(text) {
	$('#overlay').show();
	$('#overlay').find('.ajax-loader-text').text(text);
}

function hideOverlay() {
	$('#overlay').hide();
}

function displayOverlayContent(text) {
	$('#overlay-content').show();
	$('#overlay-content').find('.ajax-loader-text').text(text);
}

function hideOverlayContent() {
	$('#overlay-content').hide();
}

/*** FUNCTIONS - FEEDS ***/

function loadFeed(feedId)
{
	var href = 'feed/load/' + feedId;
	displayOverlayContent("Loading entries");
	var request = $.ajax({
		url: href,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayFeed(feedId, data.html, data.count, data.categorycount, data.valid);
		hideOverlayContent();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlayContent();
		alert("Request failed: " + textStatus);
	});
}

function updateFeed(feedId)
{
	var href = 'feed/update/' + feedId;
	displayOverlay("Updating feed.");
	var request = $.ajax({
		url: href,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayFeed(feedId, data.html, data.count, data.categorycount, data.valid);
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
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

function loadCategoryByDate(date)
{
    var catUrl = 'entry/loadbydate/' + date;
    ajaxLoadCategory('by-date', catUrl);
}

function loadLatestPage(page) {
	var catUrl = 'entry/loadlatest/' + page;
    ajaxLoadCategory('latest', catUrl);
}

function loadCategory(catId)
{
    var catUrl = '';
    if (catId == 'favourite')
    {
            catUrl = 'entry/loadfavourites';
    }
    else if (catId == 'by-date')
    {
            catUrl = 'entry/loadbydate';
	}
	else if (catId == 'latest')
    {
            catUrl = 'entry/loadlatest';
    }
    else
    {
            catUrl = 'category/load/' + catId;
    }
    ajaxLoadCategory(catId, catUrl);
}

function ajaxLoadCategory(catId, catUrl)
{
	displayOverlayContent("Loading entries");
	var request = $.ajax({
		url: catUrl,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayCategory(catId, data.html, data.count);
		hideOverlayContent();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlayContent();
		alert("Request failed: " + textStatus);
	});
}

function displayCategory(catId, catHtml, catCount, feedsCounts)
{
	$('.load-feed-link').removeClass('active');
	$('li.category').removeClass('active');
	$('li.load-feed-link').removeClass('active');
	$('li.category[data-cat-id=' + catId + ']').addClass('active');
	$("#feed-content").html(catHtml);
	positionFeedEntries();
	setCountForCategory(catId, catCount);
	for (var i in feedsCounts)
	{
		setCountForFeed(i, feedsCounts[i]);
	}
	scrollToActiveEntry('feed-content');
	$('#entry-navigation-links').show();
}

function setCountForCategory(catId, catCount)
{
	$('li.category[data-cat-id=' + catId + ']').find('.category-count').html(catCount);
}

function updateCategory(catId)
{
	displayOverlay("Updating category");
	var request = $.ajax({
		url: 'category/update/' + catId,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayCategory(catId, data.html, data.count, data.counts);
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
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

// Show / hide the feeds from a category
$("#feed-list").on("click", "li.category div i", function(e) {
	e.preventDefault();
	e.stopPropagation();
	$(this).parents('li.category').find('ul.feeds').toggle();
	$(this).toggleClass('fa-caret-right fa-caret-down');
});

// Open a category
$("#feed-list").on("click", "li.category div", function(e) {
	e.preventDefault();
	var id = $(this).parents('li.category').data('cat-id');
	loadCategory(id);
});

// Load favourites
$("#favourite").on("click", "li.category div", function(e) {
	e.preventDefault();
	var id = $(this).parents('li.category').data('cat-id');
	loadCategory(id);
});

// Load by date
$("#by-date").on("click", "li.category div", function(e) {
	e.preventDefault();
	var id = $(this).parents('li.category').data('cat-id');
	loadCategory(id);
});

// Load by date
$("#latest").on("click", "li.category div", function(e) {
	e.preventDefault();
	var id = $(this).parents('li.category').data('cat-id');
	loadCategory(id);
});

$("#feed-content").on("click", "a.load-date", function(e) {
	e.preventDefault();
	var date = $(this).attr('data-date');
	loadCategoryByDate(date);
});

$("#feed-content").on("click", "a.load-latest", function(e) {
	e.preventDefault();
	var page = $(this).attr('data-page');
	loadLatestPage(page);
});

// Open a feed
$("#feed-list").on("click", "li.load-feed-link", function(e) {
	e.preventDefault();
	var feedId = $(this).attr('data-id');
	loadFeed(feedId);
});

// Open links in new tabs
$('#feed-content').on('click', '.entry-content a', function(e) {
	$(this).attr('target','_blank');
});

// Open / Close an entry
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
				+ 'sandbox="allow-same-origin allow-scripts allow-forms" ></iframe>');
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
	displayOverlay("Updating feed");
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
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
		alert("Request failed: " + textStatus);
	});

});

// Click on "Edit a feed"
$("#feed-content").on("click", "a.feed-edit", function(e) {
	e.preventDefault();
	$("#feed-content").find("div#slider-edit-feed").slideToggle();
});

// Close the feed edition slider
$("#feed-content").on("click", "div#slider-edit-feed a.cancel-edit-feed", function(e) {
	e.preventDefault();
	$("#feed-content").find("div#slider-edit-feed").slideUp();
});

// Confirm the edition of a feed
$("#feed-content").on("click", "div#slider-edit-feed a.confirm-edit-feed", function(e) {
	e.preventDefault();
	displayOverlay("Saving feed");
	var request = $.ajax({
		url: "feed/edit",
		type: "POST",
		dataType: "json",
		data: $("#feed-content").find("#form-edit").serialize()
	});
	request.done(function(data) {
		if (data.result == '1')
		{
			$('#load-feed-link-' + data.feedid).find('span.feed-title').html(data.feedtitle);
			if (data.newcat)
			{
				var el = $('#load-feed-link-' + data.feedid).detach();
				el.attr('data-cat-id', data.newcat);
				$('li.category[data-cat-id="' + data.newcat + '"]').find('ul.feeds').prepend(el);
			}
			$("#feed-content").find("div#slider-edit-feed").slideUp;
			$("#feed-content").find("a.feed-refresh").click();
		}
		else if (data.errors)
		{
			$.each(data.errors, function(i, item) {
				$("#feed-content").find("#slider-edit-feed").find("div.errors").append('<div class="error-message">' + item + '</div>');
			});
		}
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		$("#feed-content").find("#slider-edit-feed").find("div.errors").append('<div class="error-message">' + textStatus + '</div>');
		hideOverlay();
	});
});

// Update all feeds
$('.link-update-all').click(function(e) {
	e.preventDefault();
	displayOverlay("Updating all feeds");
	var request = $.ajax({
		url: 'feed/forceupdateall',
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$("#feed-content").html('');
		$("list-feeds").html(msg);
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
		alert("Request failed: " + textStatus);
	});
});

// Mark all entries from a feed as read
$("#feed-content").on("click", ".feed-markread", function(e) {
	e.preventDefault();
	displayOverlay("Marking feed as read");
	var id = $(this).attr('data-id');
	var request = $.ajax({
		url: 'feed/markread/'+id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayFeed(data.feedId, data.html, data.count, data.categorycount, data.valid);
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
		alert("Request failed: " + textStatus);
	});

});

$("#feed-content").on("click", ".feed-marknotread", function(e) {
	e.preventDefault();
	displayOverlay("Marking feed as not read");
	var id = $(this).attr('data-id');
	var request = $.ajax({
		url: 'feed/marknotread/'+id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayFeed(data.feedId, data.html, data.count, data.categorycount, data.valid);
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
		alert("Request failed: " + textStatus);
	});
});

// Mark all entries from a date as read
$("#feed-content").on("click", ".date-markread", function(e) {
	e.preventDefault();
	displayOverlayContent("Marking date as read");
	var id = $(this).attr('data-id');
	var request = $.ajax({
		url: 'entry/markreadbydate/'+id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayCategory('by-date', data.html, data.count);
		hideOverlayContent();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlayContent();
		alert("Request failed: " + textStatus);
	});

});

$("#feed-content").on("click", ".date-marknotread", function(e) {
	e.preventDefault();
	displayOverlayContent("Marking date as not read");
	var id = $(this).attr('data-id');
	var request = $.ajax({
		url: 'entry/markunreadbydate/'+id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayCategory('by-date', data.html, data.count);
		hideOverlayContent();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlayContent();
		alert("Request failed: " + textStatus);
	});
});

// Mark all entries from a category as read
$("#feed-content").on("click", ".category-markread", function(e) {
	e.preventDefault();
	displayOverlay("Marking category as read");
	var id = $(this).attr('data-id');
	var request = $.ajax({
		url: 'category/markread/'+id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayCategory(id, data.html, data.count, data.counts);
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
		alert("Request failed: " + textStatus);
	});

});

$("#feed-content").on("click", ".category-marknotread", function(e) {
	e.preventDefault();
	displayOverlay("Marking category as not read");
	var id = $(this).attr('data-id');
	var request = $.ajax({
		url: 'category/marknotread/'+id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayCategory(id, data.html, data.count, data.counts);
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
		alert("Request failed: " + textStatus);
	});
});

// Show the modal to Delete a feed (and its entries)
$("#feed-content").on("click", ".delete-feed", function(e) {
	e.preventDefault();
	$("#feed-content").find("#slider-delete-feed").slideToggle();
});

// Close the feed deletion modal
$("#feed-content").on("click", "#slider-delete-feed .cancel-delete-feed", function(e) {
	e.preventDefault();
	$("#feed-content").find("#slider-delete-feed").slideUp();
});

// Delete a feed (and its entries)
$("#feed-content").on("click", "#slider-delete-feed .confirm-delete-feed", function(e) {
	e.preventDefault();
	displayOverlay("Deleting feed");
	var id = $(this).attr("data-id");
	var request = $.ajax({
		url: 'feed/delete/' + id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		if (data.result === 1)
		{
			$("#load-feed-link-"+id).hide();
			$("#feed-content").find("#slider-delete-feed").slideUp();
			$("#feed-content").html("");
		}
		else if (data.errors)
		{
			$.each(data.errors, function(i, item) {
				$("#feed-content").find("#slider-delete-feed").find("div.errors").append('<div class="error-message">' + item + '</div>');
			});
		}
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
		$("#feed-content").find("#slider-delete-feed").find("div.errors").append('<div class="error-message">' + textStatus + '</div>');
	});
});

// Show all entries from a feed (read and unread)
$("#feed-content").on("click", ".show-all", function(e) {
	e.preventDefault();
	displayOverlay("Loading entries");
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
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
		alert("Request failed: " + textStatus);
	});
});

// Show only the unread entries from a feed
$("#feed-content").on("click", ".show-unread", function(e) {
	e.preventDefault();
	displayOverlay("Loading entries");
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
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
		alert("Request failed: " + textStatus);
	});
});

// Show all entries from a category (read and unread)
$("#feed-content").on("click", ".category-show-all", function(e) {
	e.preventDefault();
	displayOverlay("Loading entries");
	var id = $(this).data("id");
	var request = $.ajax({
		url: 'category/load/' + id + '/1',
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayCategory(id, data.html, data.count, data.counts);
		$('.category-show-unread').parent().show();
		$('.category-show-all').parent().hide();
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
		alert("Request failed: " + textStatus);
	});
});

// Show only the unread entries from a feed
$("#feed-content").on("click", ".category-show-unread", function(e) {
	e.preventDefault();
	displayOverlay("Loading entries");
	var id = $(this).data("id");
	var request = $.ajax({
		url: 'category/load/' + id + '/0',
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		displayCategory(id, data.html, data.count, data.counts);
		$('.category-show-unread').parent().hide();
		$('.category-show-all').parent().show();
		hideOverlay();
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
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
	$("#load-entry-link-" + id).parent().addClass('read');
	var request = $.ajax({
		url: 'entry/markread/' + id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		setCountForFeed(data.feedId, data.feedCount, data.categoryCount);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
}

function markEntryNotRead(id)
{
	$("#load-entry-link-" + id).parent().removeClass('read');
	var request = $.ajax({
		url: 'entry/markunread/' + id,
		type: "GET",
		dataType: "json"
	});
	request.done(function(data) {
		setCountForFeed(data.feedId, data.feedCount, data.categoryCount);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
}

//Mark an entry as favourite
$("#feed-content").on('click', '.toggle-favourite a', function(e) {
	e.preventDefault();
	var id = $(this).attr('data-id');
	toggleEntryFavourite(id);
});

function toggleEntryFavourite(id)
{
	var favourite = $("#load-entry-link-" + id).parent().hasClass('favourite');
	if (favourite)
	{
		markEntryNotFavourite(id);
	}
	else
	{
		markEntryFavourite(id);
	}
}

function markEntryFavourite(id)
{
	$("#load-entry-link-" + id).parent().addClass('favourite');
	var request = $.ajax({
		url: 'entry/markfavourite/' + id,
		type: "GET",
		dataType: "json"
	});
        request.done(function(data) {
		setCountForFeed(data.feedId, data.feedCount, data.categoryCount);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
}

function markEntryNotFavourite(id)
{
	$("#load-entry-link-" + id).parent().removeClass('favourite');
	var request = $.ajax({
		url: 'entry/markunfavourite/' + id,
		type: "GET",
		dataType: "json"
	});
        request.done(function(data) {
		setCountForFeed(data.feedId, data.feedCount, data.categoryCount);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
}

//Mark an entry as to read
$("#feed-content").on('click', '.toggle-toread a', function(e) {
	e.preventDefault();
	var id = $(this).attr('data-id');
	toggleEntryToRead(id);
});

function toggleEntryToRead(id)
{
	var toread = $("#load-entry-link-" + id).parent().hasClass('toread');
	if (toread)
	{
		markEntryNotToRead(id);
	}
	else
	{
		markEntryToRead(id);
	}
}

function markEntryToRead(id)
{
	$("#load-entry-link-" + id).parent().addClass('toread');
	var request = $.ajax({
		url: 'entry/marktoread/' + id,
		type: "GET",
		dataType: "json"
	});
        request.done(function(data) {
		setCountForFeed(data.feedId, data.feedCount, data.categoryCount);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
}

function markEntryNotToRead(id)
{
	$("#load-entry-link-" + id).parent().removeClass('toread');
	var request = $.ajax({
		url: 'entry/markuntoread/' + id,
		type: "GET",
		dataType: "json"
	});
        request.done(function(data) {
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

// Update the entries count every 10 minutes
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
	if ($('div.modal-backdrop').css('display') == 'none')
	{
		event.preventDefault();
		// handle cursor keys
		if (event.keyCode == 80) // P
		{
			openPreviousEntry();
		}
		else if (event.keyCode == 78) // N
		{
			openNextEntry();
		}
		else if (event.keyCode == 27) // Escape
		{
			$('div.slider').slideUp();
		}
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
	$("#slider-new-feed").find("div.errors").html('');
	$("#slider-new-feed").find("#feed-url").val('');
	$('#slider-new-feed').slideToggle();
});

// Close the feed deletion modal
$("#slider-new-feed").on("click", ".cancel-new-feed", function(e) {
	e.preventDefault();
	$("#slider-new-feed").slideUp();
	$("#slider-new-feed").find("div.errors").html('');
	$("#slider-new-feed").find("#feed-url").val('');
});

// Delete a feed (and its entries)
$("#slider-new-feed").on("click", ".confirm-new-feed", function(e) {
	e.preventDefault();
	var form = $(this).closest("#slider-new-feed").find("form.feed-form");
	var feedUrlValue = form.find("#feed-url").val();
	var catIdValue = form.find("#feed-category").val();
	$("#slider-new-feed").find("div.errors").html('');
	var request = $.ajax({
		url: 'feed/add',
		type: "POST",
		dataType: "json",
		data: {feedUrl: feedUrlValue, feedCategory: catIdValue}
	});
	request.done(function(data, textStatus, jqXHR) {
		if (data.errors)
		{
			$.each(data.errors, function(i, item) {
				$("#slider-new-feed").find("div.errors").append('<div class="error-message">' + item + '</div>');
			});
		}
		else
		{
			addNewFeed(data.catId, data.feedId, data.feedName, data.feedCount, data.feedUrl);
			$("#slider-new-feed").slideUp();
		}
	});
	request.fail(function(jqXHR, textStatus) {
		$("#slider-new-feed").find("div.errors").append('<div class="error-message">' + textStatus + '</div>');
	});
});

function addNewFeed(catId, feedId, feedName, feedCount, feedUrl)
{
	var template = $("<li class='load-feed-link empty not-valid' id='load-feed-link-1' data-href='feed/load/1' data-cat-id='2' data-id='1' data-viewtype='rss' data-baselink='http://friendfeed.com/'>"
			+ "<a href='feed/load/1'><span class='feed-title'>Title</span><span class='feed-count'></span></a></li>");

	template.attr('id', 'load-feed-link-' + feedId);
	template.attr('data-href', 'feed/load/' + feedId);
	template.attr('data-cat-id', catId);
	template.attr('data-id', feedId);
	template.attr('data-baselink', feedUrl);
	template.find('a').attr('href', 'feed/load/' + feedId);
	template.find('span.feed-title').html(feedName);

	var cat = $('li.category[data-cat-id="' + catId + '"]').find("ul.feeds");

	cat.append(template);
}

$("#slider-import-opml").find("form").submit(function(e) {
	return false;
});

//Show the modal to Add a feed
$("#header").on("click", "#import-opml", function(e) {
	e.preventDefault();
	$("#slider-import-opml").find("div.errors").html('');
	$("#slider-import-opml").find("#feed-url").val('');
	$('#slider-import-opml').slideToggle();
});

// Close the feed deletion modal
$("#slider-import-opml").on("click", ".cancel-import-opml", function(e) {
	e.preventDefault();
	$("#slider-import-opml").slideUp();
	$("#slider-import-opml").find("div.errors").html('');
	$("#slider-import-opml").find("#feed-url").val('');
});

$("#slider-import-opml").on("click", '.confirm-import-opml', function(e) {
	e.preventDefault();
	displayOverlay("Importing OPML file");

//	var	oData = new FormData($('#slider-import-opml').find('form'));
	var oData = new FormData();
	oData.append('opmlfile', $("#slider-import-opml").find("#opmlfile")[0].files[0]);

	var request = $.ajax({
	    url: '/feed/importopml',
	    data: oData,
	    cache: false,
	    contentType: false,
	    processData: false,
	    type: 'POST'
	});
	request.done(function(data) {
		displayOverlay("Updating all feeds");
        $.ajax({
			url: "/feed/forceupdateall",
			type: "GET",
			success: function(data2) {
				hideOverlay();
			}
		});
	});
	request.fail(function(jqXHR, textStatus) {
		hideOverlay();
		$("#slider-import-opml").find("div.errors").append('<div class="error-message">' + textStatus + '</div>');
	});
});

$('#left-menu').on("click", '#left-menu-toggle', function(e){
   $('#left-menu').toggleClass("hidden");
   $('#left-menu-toggle i.fa-arrow-right').toggle();
   $('#left-menu-toggle i.fa-arrow-left').toggle();
});

//Bind to the resize event of the window object
$(window).on("resize", function () {
    // Set .right's width to the window width minus 480 pixels
	var height = $(window).height() - $('div#left-menu').offset().top;
	var topHeight = $('div#left-menu-top').outerHeight();
    $('div#left-menu').height(height);
    $('div#left-menu-inner').height(height);
    $('div#feed-list-container').height(height - topHeight);
    $('div#feed-list-container').css({ top: topHeight });
    positionFeedEntries();
// Invoke the resize event immediately
}).resize();


