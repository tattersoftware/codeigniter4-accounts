<?php

use Myth\Auth\Models\UserModel;
use Tatter\Accounts\Entities\Account;
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
		$model = $this->getPrivateProperty($this->handler, 'source');

		$this->assertEquals('users', $model->table);
	}

	public function testCanChangeModel()
	{
		$this->handler->setSource(new \ModuleTests\Support\Models\NewTableModel());

		$model = $this->getPrivateProperty($this->handler, 'source');

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

	public function testUpdateWorksWithAccount()
	{
		$account = new Account(get_class($this->handler));
		$account->username = 'sakura';

		$this->handler->update(2, $account);

		$row = $this->model->find(2);

		$this->assertEquals('sakura', $row->username);
	}

	public function testAddReturnsAccount()
	{
		$data = [
			'email'         => 'roland@darktower.com',
			'username'      => 'roland',
			'password_hash' => 'press4ward',
		];

		$result = $this->handler->add($data);

		$this->assertInstanceOf('Tatter\Accounts\Entities\Account', $result);
	}

	public function testAddHasOriginal()
	{
		$data = [
			'email'         => 'roland@darktower.com',
			'username'      => 'roland',
			'password_hash' => 'press4ward',
		];

		$result = $this->handler->add($data);

		$row = $this->model->find($result->id);

		$this->assertEquals($row, $result->original());
	}

	public function testRemoveDeletes()
	{
		$this->handler->remove(2);

		$this->assertNull($this->model->find(2));
	}

	public function testWrapCreatesAccount()
	{
		$method = $this->getPrivateMethodInvoker($this->handler, 'wrap');

		$data = [
			'username' => 'foobar',
			'active'   => 1,
		];

		$result = $method($data);

		$this->assertInstanceOf('Tatter\Accounts\Entities\Account', $result);
	}

	public function testWrapStandardizesFields()
	{
		$method = $this->getPrivateMethodInvoker($this->handler, 'wrap');

		$data = [
			'username' => 'foobar',
			'active'   => 1,
		];

		$result = $method($data);

		$this->assertEquals(1, $result->valid);
	}

	public function testWrapKeepsOriginal()
	{
		$method = $this->getPrivateMethodInvoker($this->handler, 'wrap');

		$data = [
			'username' => 'foobar',
			'active'   => 1,
		];

		$result = $method($data);

		$this->assertEquals($data, $result->original());
	}

	public function testUnwrapReturnsArray()
	{
		$method = $this->getPrivateMethodInvoker($this->handler, 'unwrap');

		$account = new Account(get_class($this->handler));
		$account->email = 'janus@narnia.net';

		$result = $method($account);

		$this->assertIsArray($result);
	}

	public function testUnwrapReformatsData()
	{
		$method = $this->getPrivateMethodInvoker($this->handler, 'unwrap');

		$account = new Account(get_class($this->handler));
		$account->email = 'janus@narnia.net';
		$account->valid = true;

		$expected = [
			'email'  => 'janus@narnia.net',
			'active' => true,
		];
		$result = $method($account);

		$this->assertEquals($expected, $result);
	}
}
