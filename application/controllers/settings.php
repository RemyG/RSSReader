<?php

class SettingsController extends Controller {
	
	function index()
	{
		$template = $this->loadView('settings_view');
		$template->set('pageTitle', PROJECT_NAME);
		$template->set('pageDescription', 'Welcome to PFP - Main page');
		$categories = CategoryQuery::create()->findByParentCategoryId(1);
		$template->set('categories', $categories);
  		$template->render();
	}
    
}

?>
