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
	<!--<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/unsemantic-grid-responsive-no-ie7.css" type="text/css" media="screen" />-->
	<link rel="stylesheet/less" href="<?php echo BASE_URL; ?>static/css/style.less" type="text/css" media="screen" />

	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/less-1.3.3.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/jquery-ui.min.js"></script>
</head>
<body>

	<div class="grid-container">

		<div id="left-menu" class="grid-25">

			<div class="fixed">

				<ul>
					<li class="list-feed-header"><a href="/">Feeds</a></li>
					<li class="list-feed-action"><a href="/feed/add"><i class="icon-plus-sign"> </i> New feed</a></li>
					<li class="list-feed-action"><a href="/feed/importopml"><i class="icon-download"> </i> Import OPML</a></li>
					<?php
						$c = new Criteria();
						$c->add(EntryPeer::READ, 0);
						foreach ($feeds as $feed) {
							echo '<li class="load-feed-link" data-href="feed/load/'.$feed->getId().'">
									<div class="feed-title">'.$feed->getTitle().'</div>
									<div class="feed-count">'.$feed->countEntrys($c).'</div></li>';
						}
					?>
				</ul>

			</div>
			
		</div>