<?php

/**
 * @covers FilterUtils
 */
class FilterUtilsTest extends PHPUnit_Framework_TestCase
{

	public function testSanitizeEntry()
	{
		$entries = array('key1' => 1, 'key2' => 'A');
		$this->assertEquals(NULL, FilterUtils::sanitizeEntry($entries, 'key3', FILTER_VALIDATE_INT));
		$this->assertEquals(false, FilterUtils::sanitizeEntry($entries, 'key2', FILTER_VALIDATE_INT));
		$this->assertEquals(1, FilterUtils::sanitizeEntry($entries, 'key1', FILTER_VALIDATE_INT));
		$this->assertEquals('A', FilterUtils::sanitizeEntry($entries, 'key2', FILTER_SANITIZE_STRING));
	}

	public function testSatinizeVar()
	{
		$this->assertEquals(1, FilterUtils::sanitizeVar(1, FILTER_VALIDATE_INT));
		$this->assertEquals('A', FilterUtils::sanitizeVar('A', FILTER_SANITIZE_STRING));
		$this->assertEquals(false, FilterUtils::sanitizeVar('A', FILTER_VALIDATE_INT));
	}
}

?>