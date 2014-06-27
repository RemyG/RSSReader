<?php

/**
 * @covers SessionUtils
 */
class SessionUtilsTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		@session_start();
	}

	protected function tearDown()
	{
		@session_destroy();
	}

	public function testSet()
	{
		SessionUtils::set("key", "val");
		$this->assertEquals("val", $_SESSION["key"]);
	}

	public function testGet()
	{
		SessionUtils::set("key", "val");
		$this->assertEquals("val", SessionUtils::get("key"));
		$this->assertEquals(null, SessionUtils::get("unknown-key"));
	}

	public function testGetCurrentUser()
	{
		$this->assertEquals(null, SessionUtils::getCurrentUser());
		SessionUtils::set("user-login", "login");
		$this->assertEquals("login", SessionUtils::getCurrentUser());
	}

	public function testDestroy()
	{
		SessionUtils::destroy();
		$this->assertEquals(false, session_id());
	}
}

?>