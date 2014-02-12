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
		$errors = array();
		$feed = null;
		if (array_key_exists('feedUrl', $_POST) && array_key_exists('feedCategory', $_POST))
		{
			$feedUrl = $_POST['feedUrl'];
			$feedCategoryId = $_POST['feedCategory'];
			$feedCategory = CategoryQuery::create()->findPK($feedCategoryId);
			$feed = $this->importFeed($feedUrl, $errors, $feedCategory);
			$template->set('errors', $errors);
		}
		else
		{
			$errors[] = "Feed URL or Category not set.";
		}
		
		if ($feed != null)
		{
			$return = array("catId" => $feed->getCategoryId(), "feedId" => $feed->getId(), "feedName" => $feed->getTitle(), "feedCount" => 0, "feedUrl" => $feed->getBaseLink());
			echo json_encode($return);
		}
		else
		{
			foreach ($errors as $error)
			{
				echo $error;
			}
		}
		
//   		$template->render();
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
		$c = new Criteria();
		$c->add(EntryPeer::READ, 0);
		return json_encode(array(
			'feedId' => $feed->getId(),
			'count' => $feed->countEntrys($c),
			'categorycount' => $feed->getCategory()->countEntrys($c), 
			'valid' => $feed->getValid(),
			'html' => $template->renderString()
			));
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
				$logFile = LOG_DIR."import-logs-".date("Ymd-his").".log";
				$opmlContent = simplexml_load_file($_FILES['opmlfile']['tmp_name']);
				$rootCat = CategoryQuery::create()->findPK(1);
				$errors = $this->recursiveOpmlImport($opmlContent->body, $errors, $rootCat, $logFile);
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

	/**
	 * Update all the valid feeds.
	 * 
	 * @return string
	 */
	function updateAll()
	{
		$errors = array();
		$feeds = FeedQuery::create()->filterByValid(1)->find();
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
	
	/**
	 * Force the update of all the feeds (valid or invalid).
	 * Should be called once a day, to re-check all the feeds.
	 * 
	 * @return string
	 */
	function forceUpdateAll()
	{
		$errors = array();
		$feeds = FeedQuery::create()->find();
		foreach ($feeds as $feed)
		{
			$this->updateFeed($feed, $errors, false);
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
		$catCount = $feed->getCategory()->countEntrys($c);
		echo json_encode(array('feed' => $count, 'category' => $catCount));
	}

	function countAll()
	{
		$categories = CategoryQuery::create()->find();
		$result = array();
		$c = new Criteria();
		$c->add(EntryPeer::READ, 0);
		foreach ($categories as $category)
		{
			foreach ($category->getFeeds() as $feed)
			{
				$result[] = array(
					'feedId' => $feed->getId(),
					'feedCount' => $feed->countEntrys($c), 
					'valid' => $feed->getValid(),
					'categoryCount' => $category->countEntrys($c));
			}
		}
		echo json_encode($result);
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
	
	function markNotRead($id)
	{
		$feed = FeedQuery::create()->findPK($id);
		foreach ($feed->getEntrys() as $entry)
		{
			$entry->setRead(0);
			$entry->save();
		}
		return $this->load($id);
	}

	function delete($id)
	{
		FeedQuery::create()->findPK($id)->delete();
	}

	function setViewSource($id)
	{
		$feed = FeedQuery::create()->findPK($id);
		$feed->setViewFrame(0);
		$feed->save();
	}

	function setViewFrame($id)
	{
		$feed = FeedQuery::create()->findPK($id);
		$feed->setViewFrame(1);
		$feed->save();
	}

	function order($feedId, $catId, $order)
	{
		$category = CategoryQuery::create()->findPK($catId);
		$feed = FeedQuery::create()->findPK($feedId);
		if ($feed->getCategory()->getId() == $catId)
		{
			$i = 0;
			$movedBack = true;
			foreach ($category->getFeeds() as $tmpFeed)
			{
				if ($i < $order)
				{
					if ($feedId == $tmpFeed->getId())
					{
						$tmpFeed->setcatOrder($order);
						$movedBack = false;
					}
					else
					{
						$tmpFeed->setcatOrder($i);
						$i++;
					}
				}
				else if ($i >= $order)
				{
					if ($feedId == $tmpFeed->getId())
					{
						$tmpFeed->setcatOrder($order);
					}
					else
					{
						$tmpFeed->setcatOrder($i + 1);
						$i++;
					}
				}
				$tmpFeed->save();
			}
		}
		else
		{
			$feed->setCategory($category);
			$i = 0;
			foreach ($category->getFeeds() as $tmpFeed)
			{
				if ($i < $order)
				{
					$tmpFeed->setcatOrder($i);
					$tmpFeed->save();
				}
				else if ($i >= $order)
				{					
					$tmpFeed->setcatOrder($i + 1);
					$tmpFeed->save();
				}
				$i++;
			}
			$feed->setcatOrder($order);
			$feed->save();
		}
		
	}

	function edit($id)
	{
		if (array_key_exists('feed-id', $_POST)
			&& array_key_exists('feed-title', $_POST)
			&& array_key_exists('feed-link', $_POST)
			&& array_key_exists('feed-base-link', $_POST)
			)
		{
			$feed = FeedQuery::create()->findPK($_POST['feed-id']);
			if ($feed != null)
			{
				$feed->setTitle($_POST['feed-title']);
				$feed->setLink($_POST['feed-link']);
				$feed->setBaseLink($_POST['feed-base-link']);
				$feed->save();
			}
			return json_encode(array('result' => '1', 'feedtitle' => $feed->getTitle(), 'message' => 'Feed saved'));
		}
		$feed = FeedQuery::create()->findPK($id);
		return json_encode(array('result' => ''
							, 'feedid' => $feed->getId()
							, 'feedtitle' => $feed->getTitle()
							, 'feedlink' => $feed->getLink()
							, 'feedbaselink' => $feed->getBaseLink()));
	}

	private function importFeed($feedUrl, $errors, $parentCat = null, $logFile = null)
	{

		require_once(APP_DIR.'plugins/simplepie/autoloader.php');

		try
		{
			if ($logFile == null)
			{
				$logFile = LOG_DIR."import-logs-".date("Ymd").".log";
			}

			$feedSP = new SimplePie();
			$feedSP->set_feed_url((string)$feedUrl);
			$feedSP->init();

			$feed = new Feed();
			$title = $feedSP->get_title();
			/*
			if ($title == null || $title == '')
			{
				$feedSP = new SimplePie();
				$feedSP->set_feed_url((string)$feedUrl);
				$feedSP->set_timeout(20);
				$feedSP->enable_cache(false);
				$feedSP->force_feed(true);
				$feedSP->init();
				$title = $feedSP->get_title();
			}
			if (($title == null || $title == '') && class_exists('tidy'))
			{
				$feedSP = new SimplePie();
				$feedAsString = file_get_contents($feedUrl);
				$config = array(
					'input-xml'  => true,
					'output-xml' => true,
					'wrap'       => false);
				$tidy = new tidy();
				$tidy->parseString($feedAsString, $config);
				$tidy->cleanRepair();
				$feedSP->set_raw_data($tidy);
				$feedSP->set_timeout(20);
				$feedSP->enable_cache(false);
				$feedSP->force_feed(true);
				$feedSP->init();
				$feedSP->handle_content_type();
				$title = $feedSP->get_title();
			}
			*/
			if ($title == null || $title == '')
			{
				$errors[] = '[Error] Feed not imported (no title): '.(string)$feedUrl;
				$this->logToFile($logFile, '[Error] Feed not imported (no title): '.(string)$feedUrl);
				return $errors;
			}
			$feed->setTitle($feedSP->get_title());
			$feed->setUpdated(0);
			$feed->setDescription($feedSP->get_description());
			$feed->setLink((string)$feedUrl);
			$feed->setBaseLink($feedSP->get_link());
			$feed->setValid(1);
			if ($parentCat != null)
			{
				$feed->setCategory($parentCat);
			}
			$feed->save();

			/*if ($importEntries)
			{*/
				/*
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
				*/
			/*}*/
			$this->logToFile($logFile, 'Feed imported: '.$feedUrl);
			return $feed;
		}
		catch (Exception $e)
		{
			$errors[] = 'Error when importing feed '.$feedUrl.': '.$e->getMessage();
			$this->logToFile($logFile, '[Error] Error when importing feed '.$feedUrl.': '.$e->getMessage());
			return null;
		}
	}

	/**
	 * Update a feed.
	 * @param Feed $feed The feed to update
	 * @param mixed $errors A list of strings for the current errors.
	 * @param bool $invalidate If true, when a feed cannot be updated, mark the feed as not valid.
	 * @return mixed A list of strings for the current errors.
	 */
	private function updateFeed($feed, $errors, $invalidate = true)
	{
		set_time_limit(30);

		$feedUrl = $feed->getLink();

		require_once(APP_DIR.'plugins/simplepie/autoloader.php');

		try
		{
			$feedSP = new SimplePie();
			$feedSP->set_feed_url($feedUrl);
			$valid = $feedSP->init();

			if(!$valid)
			{
				if ($invalidate)
				{
					$feed->setValid(false);
					$feed->save();
				}
				return $errors;
			}
			else {
				$feed->setValid(true);
			}

			if ($feed->getUpdated(null) != null)
			{
				$lastUpdate = $feed->getUpdated(null)->getTimestamp();
			}
			else
			{
				$lastUpdate = 0;
			}

			$feed->setUpdated(new DateTime());
			$feed->setDescription($feedSP->get_description());
			$feed->setBaseLink($feedSP->get_link());
			$feed->save();

			foreach ($feedSP->get_items() as $item)
			{
				$entryUpdated = $item->get_date('U');
				/*if ($entryUpdated > $lastUpdate)
				{*/
					$link = $item->get_link();
					$entry = EntryQuery::create()->filterByFeed($feed)->filterByLink($link)->findOne();
					if ($entry == null)
					{
						$entry = new Entry();
						$entry->setLink($link);
						$entry->setFeed($feed);
					}
					if ($item->get_author() != null)
					{
						$entry->setAuthor($item->get_author()->get_name());
					}
					$entry->setPublished($item->get_date('U'));
					$entry->setUpdated($item->get_date('U'));
					$entry->setTitle($item->get_title());
					$entry->setContent($item->get_content());
					$entry->save();
				/*}*/
			}

			$feedSP = null;
		}
		catch (Exception $e)
		{
			$errors[] = 'Error when updating feed '.$feedUrl.': '.$e->getMessage();
			if ($invalidate)
			{
				$feed->setValid(false);
				$feed->save();
			}
		}
		
		$this->cleanOldEntries($feed);

		return $errors;
	}

	private function recursiveOpmlImport($xmlNode, $errors, $parentCat, $logFile = null)
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
				$errors = $this->recursiveOpmlImport($outline, $errors, $category, $logFile);
			}
		}
		else if ($xmlNode['xmlUrl'] != null)
		{
			try
			{
				$feed = new Feed();
				$title = (string)$xmlNode['title'];
				$feedUrl = (string)$xmlNode['xmlUrl'];
				$feed->setTitle($title);
				$feed->setUpdated(0);			
				$feed->setLink($feedUrl);
				if ($parentCat != null)
				{
					$feed->setCategory($parentCat);
				}
				$feed->setValid(true);
				$feed->save();				
				$this->logToFile($logFile, 'Feed imported: '.$feedUrl);
			}
			catch (Exception $e)
			{
				$this->logToFile($logFile, '[Error] Feed not imported: '.$feedUrl.' - '.$e->getMessage());
				$errors[] = 'Error when importing feed '.$feedUrl.': '.$e->getMessage();
			}
		}
		return $errors;
	}

	private function logToFile($filename, $msg)
	{ 
		// open file
		$fd = fopen($filename, "a");
		// append date/time to message
		$str = "[" . date("Y/m/d h:i:s") . "] " . $msg; 
		// write string
		fwrite($fd, $str . "\n");
		// close file
		fclose($fd);
	}
	
	private function cleanOldEntries($feed)
	{
		$all = 0;
		$read = 0;
		$entries = EntryQuery::create()->filterByFeed($feed)->orderByUpdated('desc')->find();
		foreach ($entries as $entry)
		{
			$all++;
			if ($entry->getRead() == 1)
			{
				$read++;
			}
			if ($all > 200 || ($read > 50 && $entry->getRead() == 1))
			{
				$entry->delete();
			}

		}
	}

}

?>
