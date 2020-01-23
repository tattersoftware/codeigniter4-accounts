<?php

use Stripe\Customer;
use Stripe\Stripe;
use Tatter\Accounts\Entities\Account;
use Tatter\Accounts\Handlers\StripeHandler;

class StripeHandlerTest extends ModuleTests\Support\DatabaseTestCase
{
	public function setUp(): void
	{
		parent::setUp();

		$this->handler = new StripeHandler();
	}

	public function testGetUnmatchedReturnsNull()
	{
		$this->assertNull($this->handler->get(12345));
	}

	public function testAddReturnsAccount()
	{
		$data = [
			'name'  => 'Roland Deschain',
			'email' => 'roland@darktower.com',
		];

		$result = $this->handler->add($data);

		$this->assertInstanceOf('Tatter\Accounts\Entities\Account', $result);
	}
/*
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
*/
	public function testWrapCreatesAccount()
	{
		$method = $this->getPrivateMethodInvoker($this->handler, 'wrap');

		$data = [
			'name'  => 'Captain Snuffles',
			'email' => 'snuffles@thehighseas.com',
		];

		$result = $method($data);

		$this->assertInstanceOf('Tatter\Accounts\Entities\Account', $result);
	}

	public function testWrapKeepsOriginal()
	{
		$method = $this->getPrivateMethodInvoker($this->handler, 'wrap');

		$data = [
			'name'  => 'Captain Snuffles',
			'email' => 'snuffles@thehighseas.com',
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
}
