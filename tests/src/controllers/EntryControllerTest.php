<?php

/**
 * @covers EntryController
 */
class EntryControllerTest extends PHPUnit_Framework_TestCase
{

	public function testLoad()
	{
		$cat = MockCategory::mock(2, 'Test cat');
		$feed = MockFeed::mock(5, 'Test feed', $cat);
		$entry = MockEntry::mock(3, 'Test entry', false, $feed);

		$dao = $this->getMock('EntryDAO', array('findById', 'save'));
		$dao->expects($this->any())
			->method('findById')
			->with($entry->getId())
			->will($this->returnValue($entry));
		$dao->expects($this->any())
			->method('save');

		$feedDao = $this->getMock('FeedDAO', array('countEntries'));
		$feedDao->expects($this->any())
			->method('countEntries')
			->with($feed)
			->will($this->returnValue(2));

		$catDao = $this->getMock('CategoryDAO', array('countEntries'));
		$catDao->expects($this->any())
			->method('countEntries')
			->with($cat)
			->will($this->returnValue(4));

		$controller = new EntryController($dao, $feedDao, $catDao);

		$resultFail = $controller->logicLoad(null);

		$this->assertEquals('Invalid entry id', $resultFail['error']);

		$result = $controller->logicLoad($entry->getId());

		$this->resetModified(array($result['entry']));

		$entryOut = MockEntry::mock(3, 'Test entry', true, $feed);

		$this->resetModified(array($entryOut));

		$this->assertEquals(5, $result['feedId']);
		$this->assertEquals($entryOut, $result['entry']);
		$this->assertEquals(2, $result['feedCount']);
		$this->assertEquals(4, $result['categoryCount']);
	}

	public function testCount()
	{
		$cat = MockCategory::mock(2, 'Test cat');
		$feed = MockFeed::mock(5, 'Test feed', $cat);
		$entry = MockEntry::mock(3, 'Test entry', false, $feed);

		$dao = $this->getMock('EntryDAO', array('findById'));
		$dao->expects($this->any())
			->method('findById')
			->with($entry->getId())
			->will($this->returnValue($entry));

		$feedDao = $this->getMock('FeedDAO', array('countEntries'));
		$feedDao->expects($this->any())
			->method('countEntries')
			->with($feed)
			->will($this->returnValue(2));

		$catDao = $this->getMock('CategoryDAO', array('countEntries'));
		$catDao->expects($this->any())
			->method('countEntries')
			->with($cat)
			->will($this->returnValue(4));

		$controller = new EntryController($dao, $feedDao, $catDao);

		$resultFail = $controller->logicCount(null);

		$this->assertEquals('Invalid entry id', $resultFail['error']);

		$result = $controller->logicCount($entry->getId());

		$this->assertEquals(2, $result['feed']);
		$this->assertEquals(4, $result['category']);
	}

	public function testUpdateRead()
	{
		$cat = MockCategory::mock(2, 'Test cat');
		$feed = MockFeed::mock(5, 'Test feed', $cat);
		$entry = MockEntry::mock(3, 'Test entry', false, $feed);

		$dao = $this->getMock('EntryDAO', array('findById', 'save'));
		$dao->expects($this->any())
			->method('findById')
			->with($entry->getId())
			->will($this->returnValue($entry));
		$dao->expects($this->any())
			->method('save');

		$feedDao = $this->getMock('FeedDAO', array('countEntries'));
		$feedDao->expects($this->any())
			->method('countEntries')
			->with($feed)
			->will($this->returnValue(2));

		$catDao = $this->getMock('CategoryDAO', array('countEntries'));
		$catDao->expects($this->any())
			->method('countEntries')
			->with($cat)
			->will($this->returnValue(4));

		$controller = new EntryController($dao, $feedDao, $catDao);

		$resultFail = $controller->logicUpdateRead(null, 0);

		$this->assertEquals('Invalid entry id', $resultFail['error']);

		$resultRead = $controller->logicUpdateRead($entry->getId(), 1);

		$this->resetModified(array($entry));

		$entryOutRead = MockEntry::mock(3, 'Test entry', true, $feed);
		
		$this->resetModified(array($entryOutRead));

		$this->assertEquals($entryOutRead, $entry);
		$this->assertEquals(5, $resultRead['feedId']);
		$this->assertEquals(2, $resultRead['feedCount']);
		$this->assertEquals(4, $resultRead['categoryCount']);

		$result = $controller->logicUpdateRead($entry->getId(), 0);

		$this->resetModified(array($entry));

		$entryOut = MockEntry::mock(3, 'Test entry', false, $feed);
		
		$this->resetModified(array($entryOut));

		$this->assertEquals($entryOut, $entry);
		$this->assertEquals(5, $result['feedId']);
		$this->assertEquals(2, $result['feedCount']);
		$this->assertEquals(4, $result['categoryCount']);
	}

	private function resetModified($objects)
	{
		foreach ($objects as $object) {
			$object->resetModified();
		}
	}

}
