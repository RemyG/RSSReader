<?php



/**
 * Skeleton subclass for representing a row from the 'category' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.rss-reader
 */
class Category extends BaseCategory
{

	public function countEntrys()
	{
		$count = 0;
		$c = new Criteria();
		$c->add(EntryPeer::READ, 0);
		foreach ($this->getFeeds() as $feed)
		{
			$count += $feed->countEntrys($c);
		}
		return $count;
	}

}
