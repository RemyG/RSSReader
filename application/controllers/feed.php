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
			$opmlContent = simplexml_load_file($_FILES['opmlfile']['tmp_name']);
			$rootCat = CategoryQuery::create()->findPK(1);
			$this->recursiveOpmlImport($opmlContent->body, $errors, $rootCat);
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
		try {
			$feedContent = simplexml_load_file($feedUrl);
			if ($feedContent === false)
			{
				$errors[] = 'Unable to load '.$feedUrl;
				return;
			}
		} catch (Exception $e) {
			$errors[] = 'Caught exception: '.$e->getMessage();
			return;
		}		
		if ($feedContent->getName() == 'feed')
		{
			$codeType = 'ATOM';
			$feedUpdated = $feedContent->updated;
		}
		else if ($feedContent->getName() == 'rss')
		{
			$codeType = 'RSS';
			$feedContent = $feedContent->channel;
			$feedUpdated = $feedContent->lastBuildDate;
		}
		else
		{
			$errors[] = 'Unrecognized feed type '.$feedUrl;
			return;
		}
		$feedTitle = $feedContent->title;
		
		$feedDescription = $feedContent->description;
		$type = FeedTypeQuery::create()->findOneByCode($codeType);
		$feed = new Feed();
		$feed->setTitle($feedTitle);
		$feed->setDescription($feedDescription);
		$feed->setLink($feedUrl);
		if ($parentCat != null)
		{
			$feed->setCategory($parentCat);
		}
		try
		{
			$feed->setUpdated($feedUpdated);
			$feed->setFeedType($type);
			$feed->save();
		}
		catch (Exception $e)
		{
			$errors[] = 'Caught exception: '.$e->getMessage();
			return;
		}
		

		if ($codeType == "ATOM")
		{
			foreach ($feedContent->entry as $entry)
			{
				$published = $entry->published;
				$updated = $entry->updated;
				$link = $entry->link['href'];
				$title = $entry->title;
				$content = $entry->content;
				$entry = new Entry();
				try
				{
					$entry->setPublished($published);
					$entry->setUpdated($updated);
					$entry->setLink($link);
					$entry->setTitle($title);
					$entry->setContent($content);
					$entry->setFeed($feed);
					$entry->save();
				}
				catch (Exception $e) {
					$errors[] = 'Caught exception: '.$e->getMessage();
				}				
			}
		}

		if ($codeType = "RSS")
		{
			foreach ($feedContent->item as $item)
			{
				$published = $item->pubDate;
				$updated = $item->pubDate;
				$link = $item->link;
				$title = $item->title;
				$content = $item->content;
				$description = $item->description;
				$entry = new Entry();
				try
				{
					$entry->setPublished($published);
					$entry->setUpdated($updated);
					$entry->setLink($link);
					$entry->setTitle($title);
					$entry->setContent($content);
					$entry->setDescription($description);
					$entry->setFeed($feed);
					$entry->save();
				}
				catch (Exception $e) {
					$errors[] = 'Caught exception: '.$e->getMessage();
				}	
			}
		}
	}

	private function updateFeed($feed, &$errors)
	{
		$feedUrl = $feed->getLink();
		try {
			$feedContent = simplexml_load_file($feedUrl);
			if ($feedContent === false)
			{
				$errors[] = 'Unable to load '.$feedUrl;
				return;
			}
		} catch (Exception $e) {
			$errors[] = 'Caught exception: '.$e->getMessage();
			return;
		}		
		if ($feedContent->getName() == 'feed')
		{
			$codeType = 'ATOM';
			$feedUpdated = $feedContent->updated;
		}
		else if ($feedContent->getName() == 'rss')
		{
			$codeType = 'RSS';
			$feedContent = $feedContent->channel;
			$feedUpdated = $feedContent->lastBuildDate;
		}
		else
		{
			$errors[] = 'Unrecognized feed type '.$feedUrl;
			return;
		}

		try
		{
			$dtFeedUpdated = new DateTime($feedUpdated);
			$lastUpdate = $feed->getUpdated(null);
		}
		catch (Exception $e)
		{
			$errors[] = 'Caught exception: '.$e->getMessage();
			return;
		}

		try
		{
			$feed->setUpdated(new DateTime());
			$feed->save();
		}
		catch (Exception $e)
		{
			$errors[] = 'Caught exception: '.$e->getMessage();
			return;
		}
		
		if ($dtFeedUpdated > $lastUpdate)
		{
			if ($codeType == "ATOM")
			{
				foreach ($feedContent->entry as $entry)
				{
					try
					{
						$published = $entry->published;
						$updated = $entry->updated;
						$link = $entry->link['href'];
						$title = $entry->title;
						$content = $entry->content;
						$entry = EntryQuery::create()->filterByFeed($feed)->filterByLink($link)->findOne();
						if ($entry == null) {
							$entry = new Entry();
							$entry->setLink($link);
							$entry->setFeed($feed);
						}
						$dtEntryUpdated = new DateTime($updated);
						$dtEntryPublished = new DateTime($published);
						if ($updated != null) {
							$entry->setUpdated($updated);
						} else {
							$entry->setUpdated($feedUpdated);
						}
						if ($published != null) {
							$entry->setPublished($published);
						} else {
							$entry->setPublished($feedUpdated);
						}
						$entry->setTitle($title);
						$entry->setContent($content);
						$entry->save();
					}
					catch (Exception $e) {
						$errors[] = 'Caught exception: '.$e->getMessage();
					}
				}
			}

			if ($codeType = "RSS")
			{
				foreach ($feedContent->item as $item)
				{
					try
					{
						$published = $item->pubDate;
						$updated = $item->pubDate;
						$link = $item->link;
						$title = $item->title;
						$content = $item->content;
						$description = $item->description;
						$entry = EntryQuery::create()->filterByFeed($feed)->filterByLink($link)->findOne();
						if ($entry == null) {
							$entry = new Entry();
							$entry->setLink($link);
							$entry->setFeed($feed);
						}
						$dtEntryUpdated = new DateTime($updated);
						$dtEntryPublished = new DateTime($published);
						if ($updated != null) {
							$entry->setUpdated($updated);
						}
						if ($published != null) {
							$entry->setPublished($published);
						}
						$entry->setTitle($title);
						$entry->setContent($content);
						$entry->setDescription($description);
						$entry->save();
					}
					catch (Exception $e) {
						$errors[] = 'Caught exception: '.$e->getMessage();
					}
				}
			}

		}
		
	}

	private function recursiveOpmlImport($xmlNode, &$errors, $parentCat)
	{
		if ($xmlNode->count() > 0) {
			if ($xmlNode->getName() == 'outline') {
				$category = new Category();
				$category->setName($xmlNode['title']);
				$category->setParentCategory($parentCat);
				$category->save();
			} else {
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
