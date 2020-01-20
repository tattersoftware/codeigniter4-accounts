<?php

use Myth\Auth\Models\UserModel;
use Tatter\Accounts\Handlers\MythHandler;

class MythHandlerTest extends ModuleTests\Support\DatabaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();
	}

	public function testLoadsDefaultModel()
	{
		$handler = new MythHandler();

		$model = $this->getPrivateProperty($handler, 'model');

		$this->assertEquals('users', $model->table);
	}
}
