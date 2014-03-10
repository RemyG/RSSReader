<?php

/**
 * Model class
 *
 * @author RemyG
 * @license MIT
 */
class Model {

	private $connection;

	public function __construct()
	{
		$this->connection = PDO2::getInstance();
	}

	public function to_bool($val)
	{
	    return !!$val;
	}

	public function to_date($val)
	{
	    return date('Y-m-d', $val);
	}

	public function to_time($val)
	{
	    return date('H:i:s', $val);
	}

	public function to_datetime($val)
	{
	    return date('Y-m-d H:i:s', $val);
	}

	public function prepareStatement($sql) {
		return $this->connection->prepare($sql);
	}

	public function executeStatement($statement, $parameters = null)
	{
		if($parameters != null) {
			$statement->execute($parameters);
		} else {
			$statement->execute();
		}
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $result;

	}

	public function executeStatementUpdate($statement, $parameters = null)
	{
		if($parameters != null) {
			$statement->execute($parameters);
		} else {
			$statement->execute();
		}
		$result = $statement->rowCount();
		return $result;

	}

	public function executeStatementInsert($statement, $parameters = null)
	{
		if($parameters != null) {
			$statement->execute($parameters);
		} else {
			$statement->execute();
		}
		$result = $this->connection->lastInsertId();
		return $result;
	}
}
?>
