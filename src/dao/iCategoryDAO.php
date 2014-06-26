<?php

interface iCategoryDAO
{
	public function findById($id);

	public function findByParentId($id, $order);

	public function findNextCatOrder();

	public function save($category);
}