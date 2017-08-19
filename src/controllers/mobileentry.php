<?php

class MobileEntryController extends Controller {

	function load($id)
	{
		$template = $this->loadView('mobile_entry_load_view');
		$entry = EntryQuery::create()->findPK($id);
		$entry->setRead(1);
		$entry->save();
		$template->set('entry', $entry);
		$template->set('backUrl', '/m/feed/load/'.$entry->getFeed()->getId());
		$template->set('entryId', $id);
		$template->renderMobile();
	}

	function count($id)
	{
		$entry = EntryQuery::create()->findPK($id);
		$c = CriteriaFactory::getUnreadOrFavouriteEntriesCriteria();
		$count = $entry->getFeed()->countEntrys($c);
		echo $count;
	}

	function markRead($id)
	{
		$entry = EntryQuery::create()->findPK($id);
		$entry->setRead(1);
		$entry->save();
	}

	function markUnread($id)
	{
		$entry = EntryQuery::create()->findPK($id);
		$entry->setRead(0);
		$entry->save();
	}
}

?>
