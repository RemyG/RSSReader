<?php

class MobileMainController extends Controller {

	function index()
	{
		$template = $this->loadView('mobile_main_view');
		$template->set('pageTitle', PROJECT_NAME);
		$template->set('pageDescription', 'Welcome to PFP - Main page');
		$categories = CategoryQuery::create()->findByParentCategoryId(1);
		$template->set('categoriesTree', $categories);
		$template->renderMobile();
	}

}

?>