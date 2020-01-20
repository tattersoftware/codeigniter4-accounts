<?php

use Myth\Auth\Models\UserModel;
use Tatter\Accounts\Handlers\MythHandler;

class MythHandlerTest extends ModuleTests\Support\DatabaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();

		$this->handler = new MythHandler();
	}

	public function testLoadsDefaultModel()
	{
		$model = $this->getPrivateProperty($this->handler, 'model');

		$this->assertEquals('users', $model->table);
	}

	public function testCanChangeModel()
	{
		$this->handler->setModel(new \ModuleTests\Support\Models\NewTableModel());

		$model = $this->getPrivateProperty($this->handler, 'model');

		$this->assertEquals('foobar', $model->table);
	}

	public function testGetUnmatchedReturnsNull()
	{
		$model = $this->getPrivateProperty($this->handler, 'model');

		$this->assertNull($this->handler->get(12345));
	}
}
