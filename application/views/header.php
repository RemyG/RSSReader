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
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/foundation.css" />
	<!--<link rel="stylesheet" href="<?php //echo BASE_URL; ?>static/css/unsemantic-grid-responsive-no-ie7.css" type="text/css" media="screen" />-->
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/style.less" type="text/less" media="screen" />

	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

	<script src="<?php echo BASE_URL; ?>static/js/vendor/custom.modernizr.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/less-1.3.3.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/jquery-ui.min.js"></script>

	


</head>
<body>

	<div id="overlay" class="ajax-overlay">
		<div class="ajax-loader">&nbsp;</div>
	</div>

	<div id="header" class="row">
		<div class="small-10 large-10 columns">
			<h1 class="title"><?php echo PROJECT_NAME; ?></h1>
		</div>
		<div class="small-2 large-2 columns links">
			<a href="<?php echo BASE_URL; ?>settings">Settings</a>
		</div>
	</div>


	<div class="row">

		<div id="left-menu" class="small-3 columns">

			<ul class="side-nav">
				<li class="list-feed-header"><a href="<?php echo BASE_URL; ?>">Feeds</a></li>
				<li class="divider"></li>
				<li class="list-feed-action"><a href="<?php echo BASE_URL; ?>feed/add"><i class="icon-plus-sign"> </i> New feed</a></li>
				<li class="list-feed-action"><a href="<?php echo BASE_URL; ?>feed/importopml"><i class="icon-download"> </i> Import OPML</a></li>
				<li class="list-feed-action"><a href="<?php echo BASE_URL; ?>feed/updateall" class="link-update-all"><i class="icon-repeat"> </i> Update all feeds</a></li>
				<li class="divider"></li>
				<li class="list-feed-action"><a href="<?php echo BASE_URL; ?>user/logout"><i class="icon-signout"> </i> Logout</a></li>
				<li class="divider"></li>
				<?php
					if (isset($categoriesTree))
					{
						$c = new Criteria();
						$c->add(EntryPeer::READ, 0);
						foreach ($categoriesTree as $category)
						{
							echo '<li class="category">'.$category->getName().'</li>';
							foreach ($category->getFeeds() as $feed) {
								echo '
									<li class="load-feed-link" data-href="feed/load/'.$feed->getId().'">
										<a href="feed/load/'.$feed->getId().'">
											<span class="feed-title">'.$feed->getTitle().'</span>
											<span class="feed-count">'.$feed->countEntrys($c).'</span>
										</a>
									</li>';
							}	
						}
					}
				?>
			</ul>

			<script type="text/javascript">
				// $( ".load-feed-link" ).draggable();
				// SEE - https://github.com/mjsarfatti/nestedSortable
			</script>
			
		</div>

		<div id="content" class="small-9 columns">