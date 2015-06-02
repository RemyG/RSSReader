<?php

class EntryDAO implements iEntryDAO
{

	public function findById($id)
	{
		return EntryQuery::create()
				->findPK($id);
	}
	
	public function findByDate($searchDate)
	{
		return EntryQuery::create()
				->filterByPublished(array("min" => $searchDate." 00:00:00", "max" => $searchDate." 23:59:59"))
				->orderByUpdated('desc')
				->find();
	}

	public function getFavourites()
	{
		return EntryQuery::create()
				->filterByFavourite(1)
				->orderByUpdated('desc')
				->find();
	}

	public function save($entry)
	{
		$entry->save();
	}

}
