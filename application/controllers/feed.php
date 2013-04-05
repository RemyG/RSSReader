<?php

class FeedController extends Controller {

	function index($id)
	{
		$template = $this->loadView('feed_view');
		$feed = FeedQuery::create()->findPK($id);
		$template->set('feed', $feed);
		$template->render();
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

	function load($id)
	{
		$template = $this->loadView('feed_load_view');
		$feed = FeedQuery::create()->findPK($id);
		$errors = array();
		// $this->updateFeed($feed, $errors);
		// $feed = FeedQuery::create()->findPK($id);
		$entries = EntryQuery::create()->filterByFeed($feed)->filterByRead(0)->orderByUpdated('desc')->find();
		$template->set('feed', $feed);
		$template->set('entries', $entries);
		return $template->renderString();
	}

	function importOpml()
	{
		$template = $this->loadView('feed_importopml_view');
		$template->set('pageTitle', PROJECT_NAME.' - Import OPML file');
		$template->set('pageDescription', 'Import OPML file');
		$errors = array();
		if (array_key_exists('opmlfile', $_FILES)
			&& $_FILES['opmlfile']['error'] == UPLOAD_ERR_OK		//checks for errors
			&& is_uploaded_file($_FILES['opmlfile']['tmp_name']))	//checks that file is uploaded
		{
			try
			{
				$opmlContent = simplexml_load_file($_FILES['opmlfile']['tmp_name']);
				$rootCat = CategoryQuery::create()->findPK(1);
				$this->recursiveOpmlImport($opmlContent->body, $errors, $rootCat);
			}
			catch (Exception $e)
			{
				$errors[] = 'Error while importing the OPML file';
			}
		}		
		$template->set('errors', $errors);
		$template->render();
	}

	function updateAll()
	{
		$errors = array();
		$feeds = FeedQuery::create()->find();
		foreach ($feeds as $feed)
		{
			$this->updateFeed($feed, $errors);
		}
		$feeds = FeedQuery::create()->find();
		$template = $this->loadView('feed_updateall_view');
		$template->set('feeds', $feeds);
		$template->set('errors', $errors);
		return $template->renderString();
	}

	function update($id)
	{
		$errors = array();
		$feed = FeedQuery::create()->findPK($id);
		$this->updateFeed($feed, $errors);
		return $this->load($id);
	}

	function count($id)
	{
		$feed = FeedQuery::create()->findPK($id);
		$c = new Criteria();
		$c->add(EntryPeer::READ, 0);
		$count = $feed->countEntrys($c);
		echo $count;
	}

	function markRead($id)
	{		
		$feed = FeedQuery::create()->findPK($id);
		foreach ($feed->getEntrys() as $entry)
		{
			$entry->setRead(1);
			$entry->save();
		}		
		return $this->load($id);
	}

	private function importFeed($feedUrl, &$errors, $parentCat = null)
	{

		require_once(APP_DIR.'plugins/simplepie/autoloader.php');

		$feedSP = new SimplePie();
		$feedSP->set_feed_url($feedUrl);
		$feedSP->enable_cache(false);
		$feedSP->init();

		$feed = new Feed();
		$feed->setTitle($feedSP->get_title());
		$feed->setUpdated(new DateTime());
		$feed->setDescription($feedSP->get_description());
		$feed->setLink($feedSP->get_permalink());
		if ($parentCat != null)
		{
			$feed->setCategory($parentCat);
		}
		$feed->save();

		foreach ($feedSP->get_items() as $item)
		{
			$entry = new Entry();
			$entry->setPublished($item->get_date('U'));
			$entry->setUpdated($item->get_date('U'));
			$entry->setLink($item->get_link());
			$entry->setTitle($item->get_title());
			$entry->setContent($item->get_content());
			$entry->setFeed($feed);
			$entry->save();
		}

	}

	private function updateFeed($feed, &$errors)
	{
		$feedUrl = $feed->getLink();

		require_once(APP_DIR.'plugins/simplepie/autoloader.php');

		$feedSP = new SimplePie();
		$feedSP->set_feed_url($feedUrl);
		$feedSP->enable_cache(false);
		$feedSP->init();

		$lastUpdate = $feed->getUpdated(null)->getTimestamp();

		$feed->setTitle($feedSP->get_title());
		$feed->setUpdated(new DateTime());
		$feed->setDescription($feedSP->get_description());
		$feed->save();

		foreach ($feedSP->get_items() as $item)
		{
			$entryUpdated = $item->get_date('U');
			if ($entryUpdated > $lastUpdate)
			{
				$link = $item->get_link();
				$entry = EntryQuery::create()->filterByFeed($feed)->filterByLink($link)->findOne();
				if ($entry == null)
				{
					$entry = new Entry();
					$entry->setLink($link);
					$entry->setFeed($feed);
				}
				$entry->setPublished($item->get_date('U'));
				$entry->setUpdated($item->get_date('U'));
				$entry->setTitle($item->get_title());
				$entry->setContent($item->get_content());
				$entry->save();
			}
		}
		
	}

	private function recursiveOpmlImport($xmlNode, &$errors, $parentCat)
	{
		if ($xmlNode->count() > 0)
		{
			if ($xmlNode->getName() == 'outline')
			{
				$category = new Category();
				$category->setName($xmlNode['title']);
				$category->setParentCategory($parentCat);
				$category->save();
			}
			else
			{
				$category = $parentCat;
			}
			foreach ($xmlNode->outline as $outline)
			{				
				$this->recursiveOpmlImport($outline, $errors, $category);
			}
		}
		else if ($xmlNode['xmlUrl'] != null)
		{
			$this->importFeed($xmlNode['xmlUrl'], $errors, $parentCat);
		}
	}

}

?>
