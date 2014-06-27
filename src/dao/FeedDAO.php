<?php

class FeedDAO implements iFeedDAO
{
	
	public function countEntries(Feed $feed, Criteria $criteria)
	{
		return $feed->countEntrys($criteria);
	}

}