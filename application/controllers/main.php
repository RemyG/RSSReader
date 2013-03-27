<?php

class MainController extends Controller {
	
	function index()
	{
		$template = $this->loadView('main_view');
		$template->set('pageTitle', PROJECT_NAME);
		$template->set('pageDescription', 'Welcome to PFP - Main page');		
		$feeds = FeedQuery::create()
  			->find();
  		$template->set('feeds', $feeds);
  		$template->render();
	}
    
}

?>
