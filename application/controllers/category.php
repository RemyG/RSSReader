<?php

class CategoryController extends Controller {

	function order($catId, $order)
	{
		$category = CategoryQuery::create()->findPK($catId);
		$categories = CategoryQuery::create()->orderByCatOrder()->findByParentCategoryId(1);
		$i = 0;
		$movedBack = true;
		foreach ($categories as $tmpCat)
		{
			if ($i < $order)
			{
				if ($catId == $tmpCat->getId())
				{
					$tmpCat->setCatOrder($order);
					$movedBack = false;
				}
				else
				{
					$tmpCat->setCatOrder($i);
					$i++;
				}
			}
			else if ($i >= $order)
			{
				if ($catId == $tmpCat->getId())
				{
					$tmpCat->setCatOrder($order);
				}
				else
				{
					$tmpCat->setCatOrder($i + 1);
					$i++;
				}
			}
			$tmpCat->save();
		}
	}

	function load($id, $all = null)
	{
		$category = CategoryQuery::create()->findPK($id);

		if ($all == null || $all == 0)
		{
			$entries = EntryQuery::create()
				->useFeedQuery()
					->filterByCategoryId($id)
				->endUse()
			->orderByUpdated('desc')
			->filterByRead(0)
			->find();
		}
		else
		{
			$entries = EntryQuery::create()
				->useFeedQuery()
					->filterByCategoryId($id)
				->endUse()
			->orderByUpdated('desc')
			->find();
		}

		$template = $this->loadView('category_load_view');
		$template->set('category', $category);
		$template->set('entries', $entries);
		$c = new Criteria();
		$c->add(EntryPeer::READ, 0);
		echo json_encode(array('html' => $template->renderString(), 'count' => $category->countEntrys($c)));
	}

	function update($id)
	{
		$category = CategoryQuery::create()->findPK($id);
		require_once('feed.php');
		$feedController = new FeedController();
		foreach ($category->getFeeds() as $feed)
		{
			$feedController->update($feed->getId());
		}
		$this->load($id);
	}

	function count($id)
	{
		$category = CategoryQuery::create()->findPK($id);
		echo $category->countEntrys();
	}

}