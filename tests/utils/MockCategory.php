<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MockCategory
 *
 * @author remyg
 */
class MockCategory
{
	public static function mock($id, $name)
	{
		$cat = new Category();
		$cat->setId($id);
		$cat->setName($name);
		return $cat;
	}
}
