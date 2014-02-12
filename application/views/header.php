<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content=" width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
	<meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : DEFAULT_DESCRIPTION; ?>">
	<meta name="author" content="<?php echo isset($pageAuthor) ? $pageAuthor : DEFAULT_AUTHOR; ?>">

	<title><?php echo isset($pageTitle) ? $pageTitle : DEFAULT_TITLE; ?></title>

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/jquery-ui.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/font-awesome.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/style.css" type="text/css" media="screen" />

	<link href='http://fonts.googleapis.com/css?family=Dosis:700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Gudea:400,400italic,700' rel='stylesheet' type='text/css'>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
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
				    	<li><a href="<?php echo BASE_URL; ?>feed/add" id="add-new-feed" title="Add new feed"><i class="icon-plus-sign"> </i></a></li>
				    	<li><a href="<?php echo BASE_URL; ?>feed/importopml" title="Import OPML file"><i class="icon-download"> </i></a></li>
				    	<li><a href="<?php echo BASE_URL; ?>settings" title="Settings"><i class="icon-cog"> </i></a></li>
				    	<li><a href="<?php echo BASE_URL; ?>feed/updateall" class="link-update-all" title="Update all feeds"><i class="icon-repeat"> </i></a></li>
				    	<li><a href="<?php echo BASE_URL; ?>m" title="Go to mobile version"><i class="icon-mobile-phone"> </i></a></li>
				    	<li><a href="<?php echo BASE_URL; ?>user/logout" title="Logout"><i class="icon-signout"> </i></a></li>
					</ul>
				</div>
			</div>
		</nav>
		
		<div id="modal-new-feed" class="modal fade">
			<div class="modal-header">
				<a href="#" class="close cancel-new-feed">&times;</a>
				<h3>Add a new feed</h3>
			</div>
			<div class="modal-body">
				<form method="post" class="feed-form custom">
					<fieldset>
						<label for="feed-url">Feed URL</label>
						<input type="text" length="255" name="feed-url" id="feed-url" />
						<label for="feed-category">Category</label>
						<select name="feed-category" id="feed-category" class="medium">
							<?php
								if (isset($categoriesTree))
								{
									foreach ($categoriesTree as $category)
									{
										echo '<option value="'.$category->getId().'">'.$category->getName().'</option>';
									}
								}
							?>
						</select>
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary confirm-new-feed">Add feed</a>
				<a href="#" class="btn cancel-new-feed">Cancel</a>
			</div>
		</div>
		
		<div id="modal-edit" class="modal fade">
			<div class="modal-header">
				<a href="#" class="close cancel-edit-feed">&times;</a>
				<h3>Edit feed</h3>
			</div>
			<div class="modal-body">
				<form id="form-edit">
					<fieldset>
						<input type="hidden" name="feed-id">
						<label>Feed title</label>
						<input type="text" name="feed-title">
						<label>Feed link</label>
						<input type="url" name="feed-link">
						<label>Feed base link</label>
						<input type="url" name="feed-base-link">
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary confirm-edit-feed">Save</a>
				<a href="#" class="btn cancel-edit-feed">Cancel</a>
			</div>
		</div>
		
		<div id="modal-from-dom" class="modal fade">
			<div class="modal-header">
				<a href="#" class="close cancel-delete">&times;</a>
				<h3>Delete URL</h3>
			</div>
			<div class="modal-body">
				<p>You are about to delete this feed, this procedure is irreversible.</p>
				<p>Do you want to proceed?</p>
			</div>
			<div class="modal-footer">
				<a href="#" data-id="" class="btn danger confirm-delete">Yes</a>
				<a href="#" class="btn secondary cancel-delete">No</a>
			</div>
		</div>
	
		<div class="sub-container">
	
			<div id="main-table">
		
				<div id="left-menu">
				
					<div id="left-menu-inner">
				
						<div id="feed-list-container">
		
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
														<span class="category-title">'.$category->getName().'</span>
														<span class="category-count">'.$category->countEntrys().'</span>
													</div>
													<ul class="feeds">';
			
											foreach ($category->getFeeds() as $feed) {
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
									}
								?>
							</ul>
						
						</div>
						
					</div>
	
				</div>

