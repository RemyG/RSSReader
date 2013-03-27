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
    	echo $count;
    }
}

?>
