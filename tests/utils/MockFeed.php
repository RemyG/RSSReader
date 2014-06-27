<?php

/**
 * Description of MockFeed
 *
 * @author remyg
 */
class MockFeed
{

	public static function mock($id, $title, Category $cat)
	{
		$feed = new Feed();
		$feed->setId($id);
		$feed->setTitle($title);
		$feed->setCategory($cat);
		return $feed;
	}

}
