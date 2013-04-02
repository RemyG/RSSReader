<?php

class MainController extends Controller {
	
	function index()
	{
		$template = $this->loadView('main_view');
		$template->set('pageTitle', PROJECT_NAME);
		$template->set('pageDescription', 'Welcome to PFP - Main page');	
		$categories = CategoryQuery::create()->findByParentCategoryId(1);
  		$template->set('categoriesTree', $categories);
  		$template->render();
	}
    
}

?>
