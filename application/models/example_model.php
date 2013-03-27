<?php

class Example_model extends Model {
	
	public function getSomething($id)
	{
		$stmt = $this->prepareStatement('SELECT * FROM something WHERE id= ? ');
		$result = $this->executeStatement($stmt, array($id));
		return $result;
	}

}

?>
