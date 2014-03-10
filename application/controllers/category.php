<?php

class CategoryController extends Controller {

	/**
	 * Re-order the categories by setting a specific position to a categorie, and
	 * moving the other categories if required.
	 * 
	 * @param int $catId
	 * 		The category id.
	 * @param int $order
	 * 		The new position for this category.
	 */
	function order($catId, $order)
	{
		$category = CategoryQuery::create()->findPK($catId);
		$categories = CategoryQuery::create()->orderByCatOrder('asc')->findByParentCategoryId(1);
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

	/**
	 * Open a category and all of its entries, and display it in the view <code>category_load_view</code>.
	 * 
	 * @param int $id 
	 * 		The category id.
	 * @param int $all 
	 * 		1 if we want to display all the entries, null or 0 if we want to display only the un-read entries.
	 * 
	 * @return
	 * 		A json object containing 2 keys: 'html' for the HTML representation of the entries list, and 'count'
	 * 		for the number of entries of this category.
	 */
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
		$counts = array();
		foreach ($category->getFeeds() as $feed)
		{
			$counts[$feed->getId()] = $feed->countEntrys($c);
		}
		return json_encode(array('html' => $template->renderString(), 'count' => $category->countEntrys($c), 'counts' => $counts));
	}
	
	/**
	 * Create a new category.
	 *  
	 * @return string A json array containing either the 'id' and 'name' of the new category,
	 * 		or the 'error' that happened.
	 */
	function create()
	{
		if (array_key_exists('categoryName', $_POST))
		{
			try
			{
				$catName = $_POST['categoryName'];
				$category = new Category();
				$category->setName($catName);
				$category->setParentCategoryId(1);
				$catOrder = CategoryQuery::create()
					->select('Category.ParentCategoryId')
					->withColumn('MAX(Category.CatOrder)', 'MaxCatOrder')
					->groupBy('Category.ParentCategoryId')
					->having('Category.ParentCategoryId', 1)
					->findOne();
				$category->setCatOrder($catOrder['MaxCatOrder'] + 1);
				$category->save();
				return json_encode(array('id' => $category->getId(), 'name' => $category->getName()));
			}
			catch (Exception $e)
			{
				return json_encode(array('error' => $e->getMessage()));
			}
		}
		else
		{
			return json_encode(array('error' => "Category name not set."));
		}
	}

	/**
	 * Update every feed of this category, and return the result of $this->load.
	 * 
	 * @param int $id 
	 * 		The category id.
	 * 
	 * @return The result of $this->load.
	 */
	function update($id)
	{
		$category = CategoryQuery::create()->findPK($id);
		require_once('feed.php');
		$feedController = new FeedController();
		foreach ($category->getFeeds() as $feed)
		{
			$feedController->update($feed->getId());
		}
		return $this->load($id);
	}

	/**
	 * Return the number of un-read entries for this category.
	 * 
	 * @param int $id
	 * 		The category id.
	 * 
	 * @returns The number of un-read entries.
	 */
	function count($id)
	{
		$category = CategoryQuery::create()->findPK($id);
		return $category->countEntrys();
	}

	function markRead($id)
	{
		$category = CategoryQuery::create()->findPK($id);
		foreach ($category->getFeeds() as $feed)
		{
			foreach ($feed->getEntrys() as $entry)
			{
				$entry->setRead(1);
				$entry->save();
			}
		}
		return $this->load($id);
	}

	function markNotRead($id)
	{
		$category = CategoryQuery::create()->findPK($id);
		foreach ($category->getFeeds() as $feed)
		{
			foreach ($feed->getEntrys() as $entry)
			{
				$entry->setRead(0);
				$entry->save();
			}
		}
		return $this->load($id);
	}

}