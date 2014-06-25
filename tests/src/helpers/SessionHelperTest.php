<?php

/**
 * @covers Session_helper
 */
class SessionHelperTest extends PHPUnit_Framework_TestCase
{
	protected $helper;

	protected function setUp()
	{
		@session_start();
		$this->helper = new Session_helper();
	}

	protected function tearDown()
	{
		@session_destroy();
	}

	public function testSet()
	{
		$this->helper->set("key", "val");
		$this->assertEquals("val", $_SESSION["key"]);
	}

	public function testGet()
	{
		$this->helper->set("key", "val");
		$this->assertEquals("val", $this->helper->get("key"));
		$this->assertEquals(null, $this->helper->get("unknown-key"));
	}

	public function testGetCurrentUser()
	{
		$this->assertEquals(null, $this->helper->getCurrentUser());
		$this->helper->set("user-login", "login");
		$this->assertEquals("login", $this->helper->getCurrentUser());
	}

	public function testDestroy()
	{
		$this->helper->destroy();
		$this->assertEquals(false, session_id());
	}
}

?>