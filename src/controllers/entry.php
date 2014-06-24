<?php

class EntryController extends Controller {

	function load($id)
	{
		echo $this->loadWithTemplate($id, 'entry_load_view');
	}
	
	function loadFrame($id)
	{
		echo $this->loadWithTemplate($id, 'entry_load_frame_view');
	}
	
	function loadWithTemplate($id, $templateName)
	{
		$template = $this->loadView($templateName);
		$entry = EntryQuery::create()->findPK($id);
		if ($entry->getRead() == 0)
		{
			$entry->setRead(1);
			$entry->save();
		}
		$template->set('entry', $entry);
		$c = new Criteria();
		$c->add(EntryPeer::READ, 0);
		return json_encode(array(
				'feedId' => $entry->getFeed()->getId(),
				'html' => $template->renderString(),
				'feedCount' => $entry->getFeed()->countEntrys($c),
				'categoryCount' => $entry->getFeed()->GetCategory()->countEntrys($c)));
	}	
	
	function count($id)
	{
		$entry = EntryQuery::create()->findPK($id);
		$c = new Criteria();
		$c->add(EntryPeer::READ, 0);
		$count = $entry->getFeed()->countEntrys($c);
		$catCount = $entry->getFeed()->getCategory()->countEntrys($c);
		//echo $count.','.$catCount;
		echo json_encode(array('feed' => $count, 'category' => $catCount));
	}

	function markRead($id)
	{
		$entry = EntryQuery::create()->findPK($id);
		$entry->setRead(1);
		$entry->save();
		$c = new Criteria();
		$c->add(EntryPeer::READ, 0);
		echo json_encode(array(
			'feedId' => $entry->getFeed()->getId(), 
			'feedCount' => $entry->getFeed()->countEntrys($c),
			'categoryCount' => $entry->getFeed()->GetCategory()->countEntrys($c)));
	}

	function markUnread($id)
	{
		$entry = EntryQuery::create()->findPK($id);
		$entry->setRead(0);
		$entry->save();
		$c = new Criteria();
		$c->add(EntryPeer::READ, 0);
		echo json_encode(array(
			'feedId' => $entry->getFeed()->getId(),
			'feedCount' => $entry->getFeed()->countEntrys($c),
			'categoryCount' => $entry->getFeed()->GetCategory()->countEntrys($c)));
	}

 	function markFavourite($id)
 	{
		$entry = EntryQuery::create()->findPK($id);
		$entry->setFavourite(1);
		$entry->save();
		echo json_encode(array(
			'feedId' => $entry->getFeed()->getId()
			));
	}

	function markUnfavourite($id)
	{
		$entry = EntryQuery::create()->findPK($id);
		$entry->setFavourite(0);
		$entry->save();
		echo json_encode(array(
			'feedId' => $entry->getFeed()->getId()
			));
	}

	/**
	 * Load the favourite entries
	 * 
	 * @return
	 * 		A json object containing 2 keys: 'html' for the HTML representation of the entries list, and 'count'
	 * 		for the number of entries of this category.
	 */
	function loadFavourites()
	{
		$entries = EntryQuery::create()
				->filterByFavourite(1)
				->orderByUpdated('desc')
				->find();

		$template = $this->loadView('entry_favourite_view');
		$template->set('entries', $entries);
		return json_encode(array('html' => $template->renderString()));
	}
}

?>
