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
				->withAnyParameters()
				->will($this->returnValue($entry));
		$dao->expects($this->any())
				->method('save');
		
		$feedDao = $this->getMock('FeedDAO', array('countEntries'));
		$feedDao->expects($this->any())
				->method('countEntries')
				->withAnyParameters()
				->will($this->returnValue(2));
		
		$catDao = $this->getMock('CategoryDAO', array('countEntries'));
		$catDao->expects($this->any())
				->method('countEntries')
				->withAnyParameters()
				->will($this->returnValue(4));
		
		$controller = new EntryController($dao, $feedDao, $catDao);
		
		$result = $controller->logicLoad(2);
		
		$this->assertEquals(5, $result['feedId']);
		$this->assertEquals($entry, $result['entry']);
		$this->assertEquals(1, $result['entry']->getRead());
		$this->assertEquals(2, $result['feedCount']);
		$this->assertEquals(4, $result['categoryCount']);
	}
}
