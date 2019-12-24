<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content=" width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
	<meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : DEFAULT_DESCRIPTION; ?>">
	<meta name="author" content="<?php echo isset($pageAuthor) ? $pageAuthor : DEFAULT_AUTHOR; ?>">

	<title><?php echo isset($pageTitle) ? $pageTitle : DEFAULT_TITLE; ?></title>

	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
	<script src="https://use.fontawesome.com/138187b05a.js"></script>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/perfect-scrollbar-0.4.8.min.css" type="text/css" media="screen" />

	<link href='https://fonts.googleapis.com/css?family=Dosis:700' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Gudea:400,400italic,700' rel='stylesheet' type='text/css'>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

	<script src="<?php echo BASE_URL; ?>static/js/perfect-scrollbar-0.4.8.with-mousewheel.min.js"></script>
</head>
<body>

	<div id="overlay" class="ajax-overlay">
		<div class="ajax-loader-text"></div>
		<div class="ajax-loader">&nbsp;</div>
	</div>

	<div id="overlay-content" class="ajax-overlay-content">
		<div class="ajax-loader-text"></div>
		<div class="ajax-loader">&nbsp;</div>
	</div>

	<div class="modal-backdrop"></div>

	<div id="main-container">

		<nav id="header" class="navbar navbar-fixed-top">
			<div class="navbar">
				<div class="navbar-inner">
					<a class="brand" href="<?php echo BASE_URL; ?>"><?php echo PROJECT_NAME; ?></a>
						<ul class="nav pull-right">
							<?php
								if (isset($categoriesTree))
								{
							?>
								<li><a href="<?php echo BASE_URL; ?>feed/add" id="add-new-feed" title="Add new feed"><i class="fa fa-plus-circle"> </i></a></li>
								<li><a href="<?php echo BASE_URL; ?>feed/importopml" id="import-opml" title="Import OPML file"><i class="fa fa-download"> </i></a></li>
							<?php
								}
							?>
							<li><a href="<?php echo BASE_URL; ?>settings" title="Settings"><i class="fa fa-cog"> </i></a></li>
							<li><a href="<?php echo BASE_URL; ?>feed/updateall" class="link-update-all" title="Update all feeds"><i class="fa fa-repeat"> </i></a></li>
							<li><a href="<?php echo BASE_URL; ?>m" title="Go to mobile version"><i class="fa fa-mobile-phone"> </i></a></li>
							<li><a href="<?php echo BASE_URL; ?>user/logout" title="Logout"><i class="fa fa-sign-out"> </i></a></li>
					</ul>
				</div>
			</div>
		</nav>

		<?php include 'templates/tpl_slider_new_feed.php'; ?>

		<?php include 'templates/tpl_slider_import_opml.php'; ?>

		<div class="sub-container">

			<div id="main-table">

				<?php
					if (isset($categoriesTree))
					{
				?>

				<div id="left-menu">

					<div id="left-menu-toggle">
					    <i class="fa fa-fw fa-arrow-left"> </i><i class="fa fa-fw fa-arrow-right" style="display: none"> </i>
					</div>

					<div id="left-menu-inner">

						<div id="left-menu-top">

							<ul id="favourite">
								<li class="category" data-cat-id="favourite">
									<div>
										<i class="fa fa-fw fa-star"> </i>
										<span class="category-title">Favourite</span>
									</div>
								</li>
							</ul>
							<ul id="by-date">
								<li class="category" data-cat-id="by-date">
									<div>
										<i class="fa fa-fw fa-calendar"> </i>
										<span class="category-title">By date</span>
									</div>
								</li>
							</ul>
							<ul id="latest">
								<li class="category" data-cat-id="latest">
									<div>
										<i class="fa fa-fw fa-clock-o"> </i>
										<span class="category-title">Latest</span>
									</div>
								</li>
							</ul>

						</div>

						<div id="feed-list-container">

							<ul id="feed-list">
								<?php
									$c = CriteriaFactory::getUnreadOrFavouriteEntriesCriteria();
									$i = 0;
									foreach ($categoriesTree as $category)
									{
										echo '
											<li class="category" data-cat-id="'.$category->getId().'">
												<div>
													<i class="fa fa-fw fa-caret-down"> </i>
													<span class="category-title">'.$category->getName().'</span>
													<span class="category-count">'.$category->countEntrys($c).'</span>
												</div>
												<ul class="feeds">';

										foreach ($category->getFeeds() as $feed)
										{
											$valid = $feed->getValid() == 1 ? '' : ' not-valid';
											$empty = $feed->countEntrys($c) == 0 ? ' empty' : '';
											echo '
												<li class="load-feed-link'.$empty.$valid.'" id="load-feed-link-'.$feed->getId().'"
													data-href="feed/load/'.$feed->getId().'" data-cat-id="'.$category->getId().'"
													data-id="'.$feed->getId().'" data-viewtype="'.($feed->getViewFrame() == 0 ? 'rss' : 'www').'"
														data-baselink="'.$feed->getBaseLink().'">
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
								?>
							</ul><!-- ul#feed-list -->

						</div><!-- div#feed-list-container -->

						<script type="text/javascript">
							$('div#feed-list-container').perfectScrollbar({wheelSpeed: 30, minScrollbarLength: 20});
						</script>

					</div><!-- div#left-menu-inner -->

				</div><!-- div#left-menu -->

				<?php
					}
				?>
