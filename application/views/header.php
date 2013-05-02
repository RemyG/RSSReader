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
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/style.less" type="text/less" media="screen" />

	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/less-1.3.3.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>static/js/bootstrap.min.js"></script>

</head>
<body>

	<div id="overlay" class="ajax-overlay">
		<div class="ajax-loader-text"></div>
		<div class="ajax-loader">&nbsp;</div>
	</div>

	<div id="overlay-content" class="ajax-overlay-content">
		<div class="ajax-loader">&nbsp;</div>
	</div>

	<div id="header" class="navbar navbar-fixed-top">
		
		    <div class="navbar">

				<div class="navbar-inner">
					<div class="container-fluid">
					<a class="brand" href="<?php echo BASE_URL; ?>"><?php echo PROJECT_NAME; ?></a>
				    <ul class="nav pull-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								Settings
								<b class="caret"> </b>
							</a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo BASE_URL; ?>feed/add"><i class="icon-plus-sign"> </i> New feed</a></li>
								<li><a href="<?php echo BASE_URL; ?>feed/importopml"><i class="icon-download"> </i> Import OPML</a></li>
								<li><a href="<?php echo BASE_URL; ?>feed/updateall" class="link-update-all"><i class="icon-repeat"> </i> Update all feeds</a></li>
								<li class="divider"></li>
								<li><a href="<?php echo BASE_URL; ?>settings"><i class="icon-cog"> </i> Settings</a></li>
								<li class="divider"></li>
								<li class="list-feed-action"><a href="<?php echo BASE_URL; ?>user/logout"><i class="icon-signout"> </i> Logout</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>


	<div class="container-fluid">

		<div class="row-fluid">

			<div id="left-menu">

				<ul class="nav nav-list">
					<?php
						if (isset($categoriesTree))
						{
							$c = new Criteria();
							$c->add(EntryPeer::READ, 0);
							$i = 0;
							foreach ($categoriesTree as $category)
							{
								if ($i != 0)
								{
									echo '<li class="divider"></li>';
								}
								$i++;
								echo '<li class="nav-header" data-cat-id="'.$category->getId().'">'.$category->getName().'</li>';
								foreach ($category->getFeeds() as $feed) {
									if ($feed->getValid() == 1 || $feed->countEntrys($c) > 0)
									{
										echo '
											<li class="load-feed-link" id="load-feed-link-'.$feed->getId().'" data-href="feed/load/'.$feed->getId().'" data-cat-id="'.$category->getId().'">
												<a href="feed/load/'.$feed->getId().'">
													<span class="feed-title">'.$feed->getTitle().'</span>
													<span class="feed-count">'.$feed->countEntrys($c).'</span>
												</a>
											</li>';
									}
								}	
							}
						}
					?>
				</ul>
				
			</div>

			<div id="content">