<?php

class EntryController extends Controller {

	function load($id)
	{
		$template = $this->loadView('entry_load_view');
		$entry = EntryQuery::create()->findPK($id);
		$entry->setRead(1);
		$entry->save();
		$template->set('entry', $entry);
		return $template->renderString();
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
    }

    function markUnread($id)
	{
    	$entry = EntryQuery::create()->findPK($id);
		$entry->setRead(0);
		$entry->save();
	}
}

?>
