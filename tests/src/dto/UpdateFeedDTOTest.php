<?php

/**
 * @covers UpdateFeedDTO
 */
class UpdateFeedDTOTest extends PHPUnit_Framework_TestCase
{
	protected $dto;

	protected function setUp()
	{
		$feed = new Feed();
		$feed->setId(1);
		$this->dto = new UpdateFeedDTO($feed);
	}

	public function testGetFeed()
	{
		$this->assertEquals(1, $this->dto->getFeed()->getId());
	}

	public function testIsValid()
	{
		$this->assertEquals(false, $this->dto->isValid());
		$this->dto->setValid(true);
		$this->assertEquals(true, $this->dto->isValid());
	}

	public function testIncrementNbEntriesUpdated()
	{
		$this->dto->incrementNbEntriesUpdated();
		$this->assertEquals(1, $this->dto->getNbEntriesUpdated());
		$this->dto->incrementNbEntriesUpdated();
		$this->assertEquals(2, $this->dto->getNbEntriesUpdated());
	}

	public function testAddError()
	{
		$this->assertEquals(0, sizeof($this->dto->getErrors()));
		$this->dto->addError("Error 1");
		$this->assertEquals(1, sizeof($this->dto->getErrors()));
		$this->assertEquals("Error 1", $this->dto->getErrors()[0]);
		$this->dto->addError("Error 2");
		$this->assertEquals(2, sizeof($this->dto->getErrors()));
		$this->assertEquals("Error 2", $this->dto->getErrors()[1]);
	}
}

?>