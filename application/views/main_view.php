<div id="content" class="grid-75">

	<!--<div class="fixed">-->

		<div id="feed-content">
		</div>

	<!--</div>-->

</div>

<script type="text/javascript">
$('.load-feed-link').click(function(e) {
	e.preventDefault();
	$('#overlay').show();
	var href = $(this).attr('data-href');
	var $field = $(this);
	var request = $.ajax({
		url: href,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$('.load-feed-link').removeClass('active');
		$field.addClass('active');
		$("#feed-content").html(msg);
		$('#overlay').hide();
	});
	request.fail(function(jqXHR, textStatus) {
		$('#overlay').hide();
		alert("Request failed: " + textStatus);
	});
});

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
			$field.addClass('read');
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

$("#feed-content").on("click", ".iframe-link", function(e) {

	e.preventDefault();
	var id = $(this).attr('data-id');
	markEntryRead(id);
	$(this).hide();
	$("#load-entry-div-" + id).find('.source-link').show();	
	$("#load-entry-div-" + id).find('.entry-content').css('padding', 0);
	$("#load-entry-div-" + id).find('.entry-content').html('<iframe src="' + this.href + '" width="100%" height="500px"></iframe>');

	var containerHeight = $("#content").find('.fixed').height(),
		metaHeight = $("#load-entry-link-" + id).outerHeight() + $("#load-entry-div-" + id).find('.entry-menu').outerHeight() + 5;
	$("#load-entry-div-" + id).find('iframe').height(containerHeight - metaHeight);

	scrollToActiveEntry("load-entry-link-" + id);
});

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
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});

});

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
		$("#load-entry-link-" + id).addClass('read');
		$field.siblings('.unread-link').show();
		$field.hide();
		updateCountForEntry(id);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});

});

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
		$("#load-entry-link-" + id).removeClass('read');
		$field.siblings('.read-link').show();
		$field.hide();
		updateCountForEntry(id);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});

});

$("#feed-content").on("click", ".feed-update", function(e) {

	e.preventDefault();
	$('#overlay').show();
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

$("#feed-content").on("click", ".remove-entry", function(e) {

	e.preventDefault();
	var id = $(this).attr('data-id');
	var request = $.ajax({
		url: "entry/markread/" + id,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$('#load-entry-link-'+id).parent().hide();
		$('#load-entry-div-'+id).hide();
		if ($('#load-entry-link-'+id).parent().prevUntil(".entries-date", ":visible").length == 0
			&& $('#load-entry-link-'+id).parent().nextUntil(".entries-date", ":visible").length == 0)
		{
			$('#load-entry-link-'+id).parent().prevAll(".entries-date:first").hide();
		}
		updateCountForEntry(id);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});

});

$('.link-update-all').click(function(e) {
	e.preventDefault();
	$('#overlay').show();
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
	var container = $("#content").find('.fixed'),
		scrollTo = $("#" + entryId);
	container.scrollTop(
		scrollTo.offset().top - container.offset().top + container.scrollTop()
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
		$("#load-entry-link-" + id).addClass('read');
		$("#load-entry-div-" + id).find('.unread-link').show();
		$("#load-entry-div-" + id).find('.read-link').hide();
		updateCountForEntry(id);
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
	
}

</script>