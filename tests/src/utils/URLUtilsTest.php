<?php

/**
 * @covers URLUtils
 */
class URLUtilsTest extends PHPUnit_Framework_TestCase
{
	public function testBase_url()
	{
		$this->assertEquals("BASE_URL", URLUtils::base_url());
	}

	public function testSegment_wrongIndex()
	{
		$this->assertEquals(false, URLUtils::segment(null));
		$this->assertEquals(false, URLUtils::segment("b"));
		$this->assertEquals(false, URLUtils::segment(new Feed()));
	}

	public function testSegment()
	{
		$_SERVER['REQUEST_URI'] = "test1/test2/test3";
		$this->assertEquals("test1", URLUtils::segment(0));
		$this->assertEquals("test2", URLUtils::segment(1));
		$this->assertEquals("test3", URLUtils::segment(2));
		$this->assertEquals(false, URLUtils::segment(3));
	}
}

?>