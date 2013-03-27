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
		if (array_key_exists('feed-url', $_POST))
		{
			$errors = array();
			$feedUrl = $_POST['feed-url'];
			$this->importFeed($feedUrl, $errors);
			$template->set('errors', $errors);
		}
  		$template->render();
	}

	function load($id)
	{
		$template = $this->loadView('feed_load_view');
		$feed = FeedQuery::create()->findPK($id);
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
			&& $_FILES['opmlfile']['error'] == UPLOAD_ERR_OK			//checks for errors
			&& is_uploaded_file($_FILES['opmlfile']['tmp_name']))	//checks that file is uploaded
		{
			$opmlContent = simplexml_load_file($_FILES['opmlfile']['tmp_name']);			
			$this->recursiveOpmlImport($opmlContent->body, $errors);
		}		
		$template->set('errors', $errors);
		$template->render();
	}

	private function importFeed($feedUrl, &$errors)
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

	private function recursiveOpmlImport($xmlNode, &$errors)
	{
		if ($xmlNode->count() > 0) {
			foreach ($xmlNode->outline as $outline)
			{
				$this->recursiveOpmlImport($outline, $errors);
			}
		}
		else if ($xmlNode['xmlUrl'] != null)
		{
			$this->importFeed($xmlNode['xmlUrl'], $errors);
		}
	}


    
}

?>
