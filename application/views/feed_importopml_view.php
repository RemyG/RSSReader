<h1>Import feeds from an OPML file</h1>

<form class="feed-form" method="post" enctype="multipart/form-data" id="import-opml-form" name="import-opml-form">
	<label for="opmlfile">OPML file</label>
	<input type="file" name="opmlfile" id="opmlfile">
</form>
<a href="#" id="submit-opml" class="btn">Import file</a>

<div id="result"></div>

<script type="text/javascript">

$('#import-opml-form').submit(function(e) {
	return false;
});

$('#submit-opml').click(function(e) {
	e.preventDefault();
	$('#overlay').show();
	$('#overlay').find('.ajax-loader-text').html("Importing the OPML file");

	var	oData = new FormData(document.forms.namedItem("import-opml-form"));

	var request = $.ajax({
	    url: window.location.pathname,
	    data: oData,
	    cache: false,
	    contentType: false,
	    processData: false,
	    type: 'POST',
	    success: function(data) {
	        $('#result').html(data);
	        $('#overlay').find('.ajax-loader-text').html("Updating all the feeds");
	        $.ajax({
				url: "/feed/updateall",
				type: "GET",
				success: function(data2) {
					$('#overlay').hide();
				}
			});

	    }
	});
});
</script>