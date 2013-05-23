			</div>
			<div id="footer-menu">
				<div id="entry-meta-links">
					<a id="iframe-link" 	class="entry-meta-link" data-entry-id="" data-feed-id="" href="" title="View as website">WWW</a>
					<a id="source-link" 	class="entry-meta-link" data-entry-id="" data-feed-id="" href="" title="View RSS feed">RSS</a>
					<a id="open-new-tab" 	class="entry-meta-link" data-entry-id="" href="" title="Open website in a new tab" target="_blank"><i class="icon-forward"> </i></a>
					<a id="read-link" 		class="entry-meta-link" data-entry-id="" href="" title="Mark as read"><i class="icon-check"> </i></a>
					<a id="unread-link" 	class="entry-meta-link" data-entry-id="" href="" title="Mark as unread"><i class="icon-check-empty"> </i></a>
				</div>
				<div id="entry-navigation-links">
					<a id="previous-entry-link" class="entry-navigation-link" href="" data-id="" title="Go to the previous entry">Prev</a>
					<a id="next-entry-link" class="entry-navigation-link" href="" data-id="" title="Go to the next entry">Next</a>
				</div>
				<script type="text/javascript">

// Show the content of an entry from the source
$("#entry-meta-links").on("click", '#source-link', function(e) {
	e.preventDefault();
	viewType = 'rss';
	var id = $(this).attr('data-entry-id');
	var feedId = $(this).attr('data-feed-id');
	setFeedViewAsSource(feedId);
	openEntryAsSource(id);
});

// Show an entry inside an iframe
$("#entry-meta-links").on("click", '#iframe-link', function(e) {
	e.preventDefault();
	viewType = 'www';
	var id = $(this).attr('data-entry-id');
	var feedId = $(this).attr('data-feed-id');
	setFeedViewInFrame(feedId);
	openEntryAsFrame(id, this.href);	
});

// Mark an entry as read
$("#entry-meta-links").on('click', '#read-link', function(e) {
	e.preventDefault();
	var id = $(this).attr('data-entry-id');
	markEntryRead(id);
});

// Mark an entry as not read
$("#entry-meta-links").on('click', '#unread-link', function(e) {
	e.preventDefault();
	var id = $(this).attr('data-entry-id');
	markEntryNotRead(id);
});

$("#entry-navigation-links").on("click", '#previous-entry-link', function(e) {
	e.preventDefault();
	openPreviousEntry();	
});

$("#entry-navigation-links").on("click", '#next-entry-link', function(e) {
	e.preventDefault();
	openNextEntry();	
});
				</script>
			</div>
		</div>
	</div>
</body>
</html>