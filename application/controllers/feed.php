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

	function load($id, $all = null)
	{
		$template = $this->loadView('feed_load_view');
		$feed = FeedQuery::create()->findPK($id);
		$errors = array();
		// $this->updateFeed($feed, $errors);
		// $feed = FeedQuery::create()->findPK($id);
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
				$errors = $this->recursiveOpmlImport($opmlContent->body, $errors, $rootCat);
			}
			catch (Exception $e)
			{
				$errors[] = 'Error while importing the OPML file: '.$e->getTraceAsString();
			}
			$return = "";
			foreach ($errors as $error)
			{
				$return .= '
					<div class="alert">
    					<button type="button" class="close" data-dismiss="alert">&times;</button>
    					'.$error.'
    				</div>';
			}
			return $return;
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

	function delete($id)
	{
		FeedQuery::create()->findPK($id)->delete();
	}

	private function importFeed($feedUrl, $errors, $parentCat = null)
	{

		require_once(APP_DIR.'plugins/simplepie/autoloader.php');

		try
		{
			$feedSP = new SimplePie();
			$feedSP->set_feed_url((string)$feedUrl);
			$feedSP->enable_cache(false);
			$feedSP->init();

			$feed = new Feed();
			$title = $feedSP->get_title();
			if ($title == null || $title == '')
			{
				$feedSP = new SimplePie();
				$feedSP->set_feed_url((string)$feedUrl);
				$feedSP->enable_cache(false);
				$feedSP->force_feed(true);
				$feedSP->init();
				$title = $feedSP->get_title();
				if ($title == null || $title == '')
				{
					$feedSP = new SimplePie();
					$feedAsString = file_get_contents($feedUrl);
					$config = array(
						'input-xml'  => true,
						'output-xml' => true,
						'wrap'       => false);
					// Tidy
					$tidy = new tidy();
					$tidy->parseString($feedAsString, $config);
					$tidy->cleanRepair();
					$feedSP->set_raw_data($tidy);
					$feedSP->enable_cache(false);
					$feedSP->force_feed(true);
					$feedSP->init();
					$feedSP->handle_content_type();
					$title = $feedSP->get_title();
					if ($title == null || $title == '')
					{
						$errors[] = 'Feed not imported (no title): '.(string)$feedUrl;
						return $errors;
					}
				}
			}
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
		catch (Exception $e)
		{
			$errors[] = 'Error when importing feed '.$feedUrl.': '.$e->getMessage();
		}

		$feedSP = null;

		return $errors;

	}

	private function updateFeed($feed, $errors)
	{
		$feedUrl = $feed->getLink();

		require_once(APP_DIR.'plugins/simplepie/autoloader.php');

		try
		{

			$feedSP = new SimplePie();
			$feedSP->set_feed_url($feedUrl);
			$feedSP->enable_cache(false);
			$feedSP->init();

			$lastUpdate = $feed->getUpdated(null)->getTimestamp();

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

			$feedSP = null;
		}
		catch (Exception $e)
		{
			$errors[] = 'Error when updating feed '.$feedUrl.': '.$e->getMessage();
		}

		return $errors;
		
	}

	private function recursiveOpmlImport($xmlNode, $errors, $parentCat)
	{
		if ($xmlNode->count() > 0)
		{
			if ($xmlNode->getName() == 'outline')
			{
				$category = CategoryQuery::create()->findOneByName($xmlNode['title']);
				if ($category == null || $category->getParentCategory()->getId() != $parentCat->getId())
				{
					$category = new Category();
					$category->setName($xmlNode['title']);
					$category->setParentCategory($parentCat);
					$category->save();
				}
			}
			else
			{
				$category = $parentCat;
			}
			foreach ($xmlNode->outline as $outline)
			{				
				$errors = $this->recursiveOpmlImport($outline, $errors, $category);
			}
		}
		else if ($xmlNode['xmlUrl'] != null)
		{
			$errors = $this->importFeed($xmlNode['xmlUrl'], $errors, $parentCat);
		}
		return $errors;
	}

}

?>
