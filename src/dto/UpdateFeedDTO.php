<?php

class UpdateFeedDTO
{
	private $feed;
	private $nbEntriesUpdated;
	private $errors;
	private $valid;

	public function __construct($feed)
	{
		$this->feed = $feed;
		$this->nbEntriesUpdated = 0;
		$this->errors = array();
		$this->valid = false;
	}

	public function incrementNbEntriesUpdated()
	{
		$this->nbEntriesUpdated += 1;
	}

	public function addError($error)
	{
		$this->errors[] = $error;
	}

	public function setValid($valid = true)
	{
		$this->valid = $valid;
	}

	public function getFeed()
	{
		return $this->feed;
	}

	public function getNbEntriesUpdated()
	{
		return $this->nbEntriesUpdated;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function isValid()
	{
		return $this->valid;
	}

}