<?php

class UpdateFeedDTO
{
	private $feedId;
	private $nbEntriesUpdated;
	private $errors;

	public function __construct($feedId)
	{
		$this->feedId = $feedId;
		$this->nbEntriesUpdated = 0;
		$this->errors = array();
	}

	public function incrementNbEntriesUpdated()
	{
		$this->nbEntriesUpdated += 1;
	}

	public function addError($error)
	{
		$this->errors[] = $error;
	}

	public function getFeedId()
	{
		return $this->feedId;
	}

	public function getNbEntriesUpdated()
	{
		return $this->nbEntriesUpdated;
	}

	public function getErrors()
	{
		return $this->errors;
	}

}