<?php

class CategoryDAO implements iCategoryDAO
{
	public function findById($id)
	{
		return CategoryQuery::create()->findPK($id);
	}

	public function findByParentId($id, $order)
	{
		return CategoryQuery::create()->orderByCatOrder($order)->findByParentCategoryId($id);
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
}