<?php

/**
 * Description of MockEntry
 *
 * @author remyg
 */
class MockEntry
{

	public static function mock($id, $desc, $read, Feed $feed)
	{
		$entry = new Entry();
		$entry->setId($id);
		$entry->setDescription($desc);
		$entry->setRead($read ? 1 : 0);
		$entry->setFeed($feed);
		return $entry;
	}

}
