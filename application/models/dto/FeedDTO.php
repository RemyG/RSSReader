<?php

class FeedDTO {
	
	public $id;
	public $title;
	public $description;
	public $link;
	public $updated;
	public $type;
	public $entries;
	
	public function __construct($id, $title, $description, $link, $updated, $type) {
		$this->id = $id;
		$this->title = $title;
		$this->date_crea = $date_crea;
		$this->hashname = $hashname;
	}
	
	
	
}