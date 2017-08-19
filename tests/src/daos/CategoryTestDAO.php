<?php

class CategoryTestDAO
{

	private $connection;

	public function __construct($connection)
	{
		$this->connection = $connection;
	}

	private function createCategory($id, $name, $parent_category_id, $cat_order)
	{
		$cat = new Category();
		$cat->setId($id);
		$cat->setName($name);
		$cat->setParentCategoryId($parent_category_id);
		$cat->setCatOrder($cat_order);
		return $cat;
	}

	public function findById($id)
	{
		$query = $this->connection->query("SELECT id, name, parent_category_id, cat_order FROM category WHERE id = ".$id);
		$results = $query->fetchAll(PDO::FETCH_FUNC, "createCategory");
		return $results[0];
	}

	public function findByParentId($id)
	{
		$query = $this->connection->query("SELECT id, name, parent_category_id, cat_order FROM category WHERE parent_category_id = ".$id);
		$results = $query->fetchAll(PDO::FETCH_FUNC, "createCategory");
		return $results;
	}

	public function save($category)
	{
		if ($cat->getID() != null) {
			$query = $this->connection->query("UPDATE category SET name = '".$cat->getName()."', parent_category_id = ".$cat->getParentCategoryId.", cat_order = ".$cat->getCatOrder." WHERE id = ".$cat->getId());

		}
	}

}