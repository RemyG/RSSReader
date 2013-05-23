<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />

	<meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : DEFAULT_DESCRIPTION; ?>">
	<meta name="author" content="<?php echo isset($pageAuthor) ? $pageAuthor : DEFAULT_AUTHOR; ?>">

	<meta name="viewport" content=" width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />

	<title><?php echo isset($pageTitle) ? $pageTitle : DEFAULT_TITLE; ?></title>

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/jquery-ui.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/font-awesome.min.css" type="text/css" media="screen" />
	<!--<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/bootstrap.min.css" />-->
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/style.less" type="text/less" media="screen" />

	<link href='http://fonts.googleapis.com/css?family=Dosis:700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Gudea:400,400italic,700' rel='stylesheet' type='text/css'>

	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/less-1.3.3.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/jquery-2.0.0.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/jquery-ui.min.js"></script>
	<!--<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/bootstrap.min.js"></script>-->

</head>
<body>

	<script type="text/javascript">
	<!--
	if (screen.width < 980) {
	window.location = "<?php echo BASE_URL; ?>m";
	}
	//-->
	</script>

	<div id="overlay" class="ajax-overlay">
		<div class="ajax-loader-text"></div>
		<div class="ajax-loader">&nbsp;</div>
	</div>

	<div id="overlay-content" class="ajax-overlay-content">
		<div class="ajax-loader-text"></div>
		<div class="ajax-loader">&nbsp;</div>
	</div>

	<div class="modal-backdrop"></div>

	<nav id="header" class="navbar navbar-fixed-top">
		
		    <div class="navbar">

				<div class="navbar-inner">
					<div class="container-fluid">
					<a class="brand" href="<?php echo BASE_URL; ?>"><?php echo PROJECT_NAME; ?></a>
				    <ul class="nav pull-right">
				    	<li style="display: none;"><a href="#" title="Refresh" data-href="<?php echo BASE_URL; ?>feed/updateall" 
				    		data-id="" data-type="all" id="button-refresh"><i class="icon-refresh"> </i></a></li>
				    	<li><a href="<?php echo BASE_URL; ?>feed/add" title="Add new feed"><i class="icon-plus-sign"> </i></a></li>
				    	<li><a href="<?php echo BASE_URL; ?>feed/importopml" title="Import OPML file"><i class="icon-download"> </i></a></li>
				    	<li><a href="<?php echo BASE_URL; ?>settings" title="Settings"><i class="icon-cog"> </i></a></li>
				    	<li><a href="<?php echo BASE_URL; ?>feed/updateall" class="link-update-all" title="Update all feeds"><i class="icon-repeat"> </i></a></li>
				    	<li><a href="<?php echo BASE_URL; ?>user/logout" title="Logout"><i class="icon-signout"> </i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</nav>


	<div class="container-fluid">

		<div class="row-fluid">

			<div id="left-menu">

				<ul id="feed-list">
					<?php
						if (isset($categoriesTree))
						{
							$c = new Criteria();
							$c->add(EntryPeer::READ, 0);
							$i = 0;
							foreach ($categoriesTree as $category)
							{
								echo '
									<li class="category" data-cat-id="'.$category->getId().'">										
										<div>
											<i class="icon-collapse-alt"> </i>
											'.$category->getName().'
											<span class="category-count">'.$category->countEntrys().'</span>
										</div>
										<ul class="feeds">';

								foreach ($category->getFeeds() as $feed) {
									$valid = $feed->getValid() == 1 ? '' : ' not-valid';
									$empty = $feed->countEntrys($c) == 0 ? ' empty' : '';
									echo '
										<li class="load-feed-link'.$empty.$valid.'" id="load-feed-link-'.$feed->getId().'" 
											data-href="feed/load/'.$feed->getId().'" data-cat-id="'.$category->getId().'"
											data-id="'.$feed->getId().'" data-viewtype="'.($feed->getViewFrame() == 0 ? 'rss' : 'www').'">
											<a href="feed/load/'.$feed->getId().'">
												<span class="feed-title">'.$feed->getTitle().'</span>
												<span class="feed-count">'.$feed->countEntrys($c).'</span>
											</a>
										</li>';
								}

								echo '
										</ul>
									</li>';								
							}
						}
					?>
				</ul>

				<script type="text/javascript">
				$(function() {
					$("#feed-list").sortable({
						items: "li.load-feed-link",
						update: function(event, ui)
						{
							var feedId = ui.item.data('id');
							var catId = ui.item.parents("li.category").data('cat-id');
							var order = ui.item.prevAll("li.load-feed-link").size();
							setNewOrder(feedId, catId, order);
						}
					});
				});
				function setNewOrder(feedId, catId, order)
				{
					var request = $.ajax({
						url: "feed/order/" + feedId + "/" + catId + "/" + order,
						type: "GET",
						dataType: "html"
					});
					request.done(function(msg) {
					});
					request.fail(function(jqXHR, textStatus) {
					});
				}
				</script>
				
			</div>

			<div id="content">