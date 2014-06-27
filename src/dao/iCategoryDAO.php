<?php

interface iCategoryDAO
{
	public function findById($id);

	public function findByParentId($id, $order);

	public function findNextCatOrder();
	
	/**
	 * Return the number of entries matching the criteria for the category.
	 * @param Category $category
	 * @param Criteria $criteria
	 * @return int The number of entries matching the criteria for the category.
	 */
	public function countEntries(Category $category, Criteria $criteria);

	public function save($category);
}