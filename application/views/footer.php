			</div><!-- div#main-table -->
		</div><!-- div.sun-container -->
	</div><!-- div#main-container -->
	<script>
		// Bind to the resize event of the window object
		$(window).on("resize", function () {
		    // Set .right's width to the window width minus 480 pixels
		    $('div#left-menu').height($(window).height() - $('div#left-menu').offset().top);
		    positionFeedEntries();
		// Invoke the resize event immediately
		}).resize();
	</script>
</body>
</html>