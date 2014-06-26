<?php

class EntryDAO implements iEntryDAO
{
	public function save($entry)
	{
		$entry->save();
	}

}