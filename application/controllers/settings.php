<?php

class SettingsController extends Controller {
	
	function index()
	{
		$template = $this->loadView('settings_view');
		$template->set('pageTitle', PROJECT_NAME);
		$template->set('pageDescription', 'Welcome to PFP - Main page');	
  		$template->render();
	}
    
}

?>
