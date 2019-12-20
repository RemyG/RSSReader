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
				->filterByRead(0)
					->_or()
				->filterByFavourite(1)
					->_or()
				->filterByToRead(1)
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

	public function findLatest($page)
	{
		return EntryQuery::create()
				->filterByRead(0)
					->_or()
				->filterByFavourite(1)
					->_or()
				->filterByToRead(1)
				->orderByUpdated('desc')
				->limit(100)
				->offset(100 * $page)
				->find();
	}

	public function save($entry)
	{
		$entry->save();
	}

}
