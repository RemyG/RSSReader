<?php

class EntryDAO implements iEntryDAO
{

	public function findById($id)
	{
		return EntryQuery::create()
				->findPK($id);
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
