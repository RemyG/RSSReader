<?php

class MainController extends Controller {

	function index()
	{
		$template = $this->loadView('main_view');
		$template->set('pageTitle', PROJECT_NAME);
		$template->set('pageDescription', 'Welcome to PFP - Main page');
		$categories = CategoryQuery::create()->orderByCatOrder()->findByParentCategoryId(1);
		foreach ($categories as $category)
		{
			$category->setFeeds($category->getFeeds(FeedQuery::create()->orderBycatOrder()));
		}
		$template->set('categoriesTree', $categories);
		$template->render();
	}

}

?>
