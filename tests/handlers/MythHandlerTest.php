<?php

use Myth\Auth\Models\UserModel;
use Tatter\Accounts\Handlers\MythHandler;

class MythHandlerTest extends ModuleTests\Support\DatabaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();

		$this->handler = new MythHandler();
		$this->model   = new UserModel();
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
		$this->assertNull($this->handler->get(12345));
	}

	public function testGetReturnsCorrectValues()
	{
		$account = $this->handler->get(1);

		$this->assertEquals(1, $account->id);
		$this->assertEquals('yamira@noted.com', $account->email);
		$this->assertEquals('light', $account->username);
	}

	public function testGetConvertsActive()
	{
		$this->model->update(1, ['active' => 0]);

		$account = $this->handler->get(1);

		$this->assertFalse((bool) $account->valid);
	}

	public function testUpdateChangesValues()
	{
		$this->handler->update(1, ['username' => 'dark']);

		$row = $this->model->find(1);

		$this->assertEquals('dark', $row->username);
	}
}
