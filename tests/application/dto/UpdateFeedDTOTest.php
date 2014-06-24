<?php

class UpdateFeedDTOTest extends PHPUnit_Framework_TestCase
{

	protected $dto;

    protected function setUp()
    {
        $this->dto = new UpdateFeedDTO(1);
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