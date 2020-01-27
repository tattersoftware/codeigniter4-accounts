<?php

use Tatter\Accounts\Accounts;
use Tatter\Accounts\Handlers\BaseHandler;

class FactoryTest extends \CodeIgniter\Test\CIUnitTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		
		$this->accounts = new Accounts();
	}

	public function testCanLoadInternal()
	{
		$handler = $this->accounts->myth;

		$this->assertInstanceOf('Tatter\Accounts\Handlers\BaseHandler', $handler);
	}

	public function testCanLoadAnyNamespace()
	{
		$handler = $this->accounts->dummy;

		$this->assertInstanceOf('Tatter\Accounts\Handlers\BaseHandler', $handler);
	}
}
