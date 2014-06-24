<?php

class MobileFeedController extends Controller {

	function index($id)
	{
		$template = $this->loadView('feed_view');
		$feed = FeedQuery::create()->findPK($id);
		$template->set('feed', $feed);
		$template->render();
	}

	function category($id)
	{
		$template = $this->loadView('mobile_category_load_view');
		$template->set('pageTitle', PROJECT_NAME);
		$template->set('pageDescription', 'Welcome to PFP - Main page');
		$category = CategoryQuery::create()->findPK($id);
		$template->set('category', $category);
		$template->set('backUrl', '/m/');
		$template->renderMobile();
	}

	function add()
	{
		$template = $this->loadView('feed_add_view');
		$template->set('pageTitle', PROJECT_NAME.' - New feed');
		$template->set('pageDescription', 'New feed');
		$categories = CategoryQuery::create()->findByParentCategoryId(1);
		$template->set('categories', $categories);
		if (array_key_exists('feed-url', $_POST))
		{
			$errors = array();
			$feedUrl = $_POST['feed-url'];
			$feedCategoryId = $_POST['feed-category'];
			$feedCategory = CategoryQuery::create()->findPK($feedCategoryId);
			$this->importFeed($feedUrl, $errors, $feedCategory);
			$template->set('errors', $errors);
		}
		$template->render();
	}

	function load($id, $all = null)
	{
		$template = $this->loadView('mobile_feed_load_view');
		$feed = FeedQuery::create()->findPK($id);
		$errors = array();
		if ($all == null || $all == 0)
		{
			$entries = EntryQuery::create()->filterByFeed($feed)->filterByRead(0)->orderByUpdated('desc')->find();
		}
		else
		{
			$entries = EntryQuery::create()->filterByFeed($feed)->orderByUpdated('desc')->find();
		}
		$template->set('feed', $feed);
		$template->set('entries', $entries);
		$template->set('backUrl', '/m/feed/category/'.$feed->getCategory()->getId());
		$template->set('toggleText', $all == null || $all == 0 ? 'All' : 'Unread');
		$template->set('toggleUrl', '/m/feed/load/'.$id.($all == null || $all == 0 ? '/1' : ''));
		$template->renderMobile();

	}

}

?>
