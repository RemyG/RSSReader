<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CriteriaFactory
{
	public static function getUnreadOrFavouriteEntriesCriteria()
	{
		$c = new Criteria();
		$c->addOr(EntryPeer::READ, 0);
		$c->addOr(EntryPeer::FAVOURITE, 1);
		return $c;
	}
}