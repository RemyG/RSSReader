<div id="content" class="grid-75">

	<div class="fixed">

		<div id="feed-content">
		</div>

	</div>

</div>

<script type="text/javascript">
$('.load-feed-link').click(function(e) {
	e.preventDefault();
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
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
});

$("#feed-content").on("click", ".load-entry-link", function(e) {
	e.preventDefault();
	var id = $(this).attr('data-id');
	var $field = $(this);
	var request = $.ajax({
		url: 'entry/load/' + id,
		type: "GET",
		dataType: "html"
	});
	request.done(function(msg) {
		$field.addClass('read');
		$('.load-entry-div').hide();
		$('.load-entry-link').removeClass('active');
		$field.addClass('active');
		$("#load-entry-div-" + id).find('.entry-content').html(msg);		
		$("#load-entry-div-" + id).show();
		updateCountForEntry(id);		
	});
	request.fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});
});

$("#feed-content").on("click", ".iframe-link", function(e) {

	e.preventDefault();
	var id = $(this).attr('data-id');
	$(this).hide();
	$("#load-entry-div-" + id).find('.source-link').show();
	$("#load-entry-div-" + id).find('.entry-content').css('padding', 0);
	$("#load-entry-div-" + id).find('.entry-content').html('<iframe src="' + this.href + '" width="100%" height="500px"></iframe>');

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
		$(this).hide();
		$("#load-entry-div-" + id).find('.iframe-link').show();
		$("#load-entry-div-" + id).find('.entry-content').css('padding', 5);
		$("#load-entry-div-" + id).find('.entry-content').html(msg);
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

</script>