<?php

class MobileEntryController extends Controller {

	function load($id)
	{
		$template = $this->loadView('mobile_entry_load_view');
		$entry = EntryQuery::create()->findPK($id);
		$entry->setRead(1);
		$entry->save();
		$template->set('entry', $entry);
		$template->renderMobile();
	}
    
    function count($id)
    {
    	$entry = EntryQuery::create()->findPK($id);
    	$c = new Criteria();
		$c->add(EntryPeer::READ, 0);
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
