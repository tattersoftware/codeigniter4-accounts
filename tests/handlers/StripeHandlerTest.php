<?php

use Stripe\Customer;
use Stripe\Stripe;
use Tatter\Accounts\Entities\Account;
use Tatter\Accounts\Handlers\StripeHandler;

class StripeHandlerTest extends \CodeIgniter\Test\CIUnitTestCase
{
	use \Tatter\Accounts\Test\StripeTestTrait;

	public function setUp(): void
	{
		parent::setUp();
		
		$this->accountsSetUp();

		$this->handler = new StripeHandler();
	}

	public function tearDown(): void
	{
		parent::tearDown();
		
		$this->accountsTearDown();
	}

	public function testAddReturnsAccount()
	{
		$data = [
			'name'  => self::$faker->name,
			'email' => self::$faker->email,
		];

		$result = $this->handler->add($data);

		$this->assertInstanceOf('Tatter\Accounts\Entities\Account', $result);

		// Clean up
		$result->original()->delete();
	}

	public function testGetUnmatchedReturnsNull()
	{
		// Have to turn off debug mode to ignore the exception
		$this->handler->debug = false;

		$this->assertNull($this->handler->get(12345));
	}

	public function testGetReturnsCorrectValues()
	{
		$account = $this->createStripeAccount();

		$result = $this->handler->get($account->uid());

		$this->assertEquals($account->email, $result->email);
	}

	public function testUpdateChangesValues()
	{
		$original = $this->createStripeAccount();
		
		$email = self::$faker->email;

		$result = $this->handler->update($original->uid(), ['email' => $email]);
		$this->assertTrue($result);
		
		$account = $this->handler->get($original->uid());

		$this->assertEquals($email, $account->email);
	}

	public function testRemoveDeletes()
	{
		$account = $this->createStripeAccount();

		$result = $this->handler->remove($account->uid());
		$this->assertTrue($result);

		// Have to turn off debug mode to ignore the exception
		$this->handler->debug = false;
		$result = $this->handler->get($account->uid());

		$this->assertTrue($result->original()->deleted);

		// Since the account is gone, remove it from the cache
		$this->removeCache = [];
	}
}
