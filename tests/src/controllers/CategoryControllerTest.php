<?php

/**
 * @covers CategoryController
 */
class CategoryControllerTest extends RSSReader_Tests_DatabaseTestCase
{
	public $fixtures = array(
        'category'
    );

	/**
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	public function getDataSet()
	{
		return $this->createFlatXmlDataSet(dirname(__FILE__).'/../../resources/category_controller_test.xml');
	}

	public function testOrder_wrongArguments()
	{
		$controller = new CategoryController();
		$output = $controller->logicOrder('A', 'B');
		$this->assertEquals('Invalid category id', $output['error']);

		$output = $controller->logicOrder(1, NULL);
		$this->assertEquals('Invalid position value', $output['error']);

	}

	public function testOrder_forward()
	{
		$conn = $this->getConnection()->getConnection();

		$dao = CategoryTestDTO($conn);

		$controller = new CategoryController($dao);

		$controller->logicOrder(6, 4);

		$this->resetModified($cats);
	}

	public function testOrder_backward()
	{
		$cat = $this->mockCategory(6, 'Test cat 6', 4, 1);

		$cats = array();
		$cats[] = $this->mockCategory(1, 'Test cat 1', 1, 1); // $i = 0 -> order = 0
		$cats[] = $this->mockCategory(4, 'Test cat 4', 2, 1); // $i = 1 -> order = 1
		$cats[] = $this->mockCategory(5, 'Test cat 5', 3, 1); // $i = 2 -> order = 3
		$cats[] = $this->mockCategory(6, 'Test cat 6', 4, 1); // $i = 3 -> order = 2
		$cats[] = $this->mockCategory(3, 'Test cat 3', 5, 1); // $i = 3 -> order = 4
		$cats[] = $this->mockCategory(2, 'Test cat 2', 6, 1); // $i = 4 -> order = 5

		$dao = $this->getMock('CategoryDAO', array('findById', 'findByParentId', 'save'));
		$dao->expects($this->any())
				->method('findById')
				->withAnyParameters()
				->will($this->returnValue($cat));
		$dao->expects($this->any())
				->method('findByParentId')
				->withAnyParameters()
				->will($this->returnValue($cats));
		$dao->expects($this->any())
				->method('save');

		$controller = new CategoryController($dao);

		$controller->logicOrder(6, 2);

		$this->resetModified($cats);

		$result = array();
		$result[] = $this->mockCategory(1, 'Test cat 1', 0, 1);
		$result[] = $this->mockCategory(4, 'Test cat 4', 1, 1);
		$result[] = $this->mockCategory(5, 'Test cat 5', 3, 1);
		$result[] = $this->mockCategory(6, 'Test cat 6', 2, 1);
		$result[] = $this->mockCategory(3, 'Test cat 3', 4, 1);
		$result[] = $this->mockCategory(2, 'Test cat 2', 5, 1);

		$this->assertEquals($result, $cats);
	}

	public function testCreate_ok()
	{
		$dao = $this->getMock('CategoryDAO', array('findNextCatOrder', 'save'));
		$dao->expects($this->any())
				->method('findNextCatOrder')
				->will($this->returnValue(6));
		$dao->expects($this->any())
				->method('save');

		$controller = new CategoryController($dao);

		$_POST['categoryName'] = 'Test name';

		$output = $controller->logicCreate();

		$this->assertEquals(0, $output['id']);
		$this->assertEquals('Test name', $output['name']);
		$this->assertEquals(7, $output['order']);
	}

	public function testCreate_noName()
	{
		$dao = $this->getMock('CategoryDAO');

		$controller = new CategoryController($dao);

		$output = $controller->logicCreate();

		$this->assertEquals('Category name not set', $output['error']);
	}

	public function testCreate_exception()
	{
		$dao = $this->getMock('CategoryDAO', array('findNextCatOrder', 'save'));
		$dao->expects($this->any())
				->method('findNextCatOrder')
				->will($this->returnValue(6));
		$dao->expects($this->any())
				->method('save')
				->will($this->throwException(new Exception('Error while saving')));

		$controller = new CategoryController($dao);

		$_POST['categoryName'] = 'Test name';

		$output = $controller->logicCreate();

		$this->assertEquals('Error while saving', $output['error']);
	}

	public function testLoad()
	{
		$cat = $this->mockCategory(1, 'Test cat 1', 1, 1);

		$entries = array();
		$entries[] = $this->mockEntry(1, "Entry 1");
		$entries[] = $this->mockEntry(2, "Entry 2");
		$entries[] = $this->mockEntry(3, "Entry 3");
		$entries[] = $this->mockEntry(4, "Entry 4");

		$dao = $this->getMock('CategoryDAO', array('findById', 'getUnreadEntries'));
		$dao->expects($this->any())
				->method('findById')
				->will($this->returnValue($cat));
		$dao->expects($this->any())
				->method('findById')
				->will($this->returnValue($cat));
	}

	private function mockCategory($id, $name, $catOrder, $parentCat)
	{
		$cat = new Category();
		$cat->setId($id);
		$cat->setName($name);
		$cat->setCatOrder($catOrder);
		$cat->setParentCategoryId($parentCat);
		$cat->resetModified();
		return $cat;
	}

	private function mockEntry($id, $title)
	{
		$entry = new Entry();
		$entry->setId($id);
		$entry->setTitle($title);
		return $entry;
	}

	private function resetModified($objects)
	{
		foreach ($objects as $object) {
			$object->resetModified();
		}
	}

	
}

?>