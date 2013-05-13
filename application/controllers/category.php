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

}