<?php

class CategoryDAO implements iCategoryDAO
{
	public function findById($id)
	{
		return CategoryQuery::create()->findPK($id);
	}

	public function findByParentId($id, $dir = 'asc')
	{
		return CategoryQuery::create()->orderByCatOrder($dir)->findByParentCategoryId($id);
	}

	public function findNextCatOrder()
	{
		return CategoryQuery::create()
					->select('Category.ParentCategoryId')
					->withColumn('MAX(Category.CatOrder)', 'MaxCatOrder')
					->groupBy('Category.ParentCategoryId')
					->having('Category.ParentCategoryId', 1)
					->findOne()['MaxCatOrder'];
	}

	public function save($category)
	{
		$category->save();
	}

	public function getUnreadEntries($id)
	{
		return EntryQuery::create()
				->useFeedQuery()
					->filterByCategoryId($id)
				->endUse()
				->orderByUpdated('desc')
				->filterByRead(0)
					->_or()
				->filterByFavourite(1)
				->find();
	}

	public function getAllEntries($id)
	{
		EntryQuery::create()
				->useFeedQuery()
					->filterByCategoryId($id)
				->endUse()
				->orderByUpdated('desc')
				->find();
	}

	public function countEntries(\Category $category, \Criteria $criteria)
	{
		return $category->countEntrys($criteria);
	}

}