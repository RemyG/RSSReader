<?php

interface iFeedDAO
{
	/**
	 * Return the number of entries matching the criteria for this feed.
	 * @param Feed $feed
	 * @param Criteria $criteria
	 * @return int The number of entries matching the criteria for this feed.
	 */
	public function countEntries(Feed $feed, Criteria $criteria);
	
}