<?php

/**
 * @covers Url_helper
 */
class UrlHelperTest extends PHPUnit_Framework_TestCase
{
	protected $helper;

	protected function setUp()
	{
		$this->helper = new Url_helper();
	}

	public function testBase_url()
	{
		$this->assertEquals("BASE_URL", $this->helper->base_url());
	}

	public function testSegment_wrongIndex()
	{
		$this->assertEquals(false, $this->helper->segment(null));
		$this->assertEquals(false, $this->helper->segment("b"));
		$this->assertEquals(false, $this->helper->segment(new Feed()));
	}

	public function testSegment()
	{
		$_SERVER['REQUEST_URI'] = "test1/test2/test3";
		$this->assertEquals("test1", $this->helper->segment(0));
		$this->assertEquals("test2", $this->helper->segment(1));
		$this->assertEquals("test3", $this->helper->segment(2));
		$this->assertEquals(false, $this->helper->segment(3));
	}
}

?>