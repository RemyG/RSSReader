<?php

interface iEntryDAO
{
	public function findById($id);
	
	public function getFavourites();

	public function save($entry);
}