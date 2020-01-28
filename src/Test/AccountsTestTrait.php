<?php namespace Tatter\Accounts\Test;

use Tatter\Accounts\Entities\Account;

/**
 * Class AccountsTestTrait
 *
 * A set of methods to be used for unit test cases to
 * assist with generating, creating, and removing accounts.
 */
trait AccountsTestTrait
{
	/**
	 * Accounts factory for managing handler instances.
	 *
	 * @var Tatter\Accounts\Accounts
	 */
	protected static $accounts;

	/**
	 * Faker instance for generating content.
	 *
	 * @var Faker\Factory
	 */
	protected static $faker;

	/**
	 * Cache of accounts to be removed during tearDown.
	 *
	 * @var array of [handler, ID]
	 */
	protected $accountsCache = [];

    /**
     * Initialize Faker and make sure the remove cache is clean
     */
	protected function accountsSetUp(): void
	{
		// Load the Accounts factory if it isn't already
		if (self::$accounts == null)
		{
			// Use the service in case it has been mocked
			self::$accounts = \Config\Services::accounts();
		}

		// Load Faker if it isn't already
		if (self::$faker == null)
		{
			self::$faker = \Faker\Factory::create();
		}

		// Make sure the cache is clean
		$this->accountsCache = [];
	}

    /**
     * Remove cached items added during testing
     */
	protected function accountsTearDown(): void
	{
		// Remove any test items in the cache
		while ($row = array_pop($this->accountsCache))
		{
			$this->removeAccount($row[0], $row[1]);
		}
	}

	/**
	 * Generates a random alpha-numeric UID.
	 *
	 * @param int $length
	 *
	 * @return string
	 */
	protected function generateUid(int $length = 29): string
	{
		return self::$faker->format('regexify', ['[a-zA-Z0-9]{' . $length . '}']);
	}

	/**
	 * Generates an Account with random data.
	 *
	 * @return array
	 */
	protected function generateAccount(): Account
	{
		// Generate a random UID
		$uid = $this->generateUid();

		// Start the entity
		$account = new Account(get_class(self::$faker), $uid);
		
		// Populate the fields
		$account->id       = $uid;
		$account->name     = self::$faker->name;
		$account->username = self::$faker->userName;
		$account->email    = self::$faker->email;
		$account->phone    = self::$faker->e164PhoneNumber;
		
		return $account;
	}

	/**
	 * Creates an Account on-the-fly.
	 *
	 * @param string $handler  Handler name to request of the factory
	 * @param array  $data     Array of data to override the defaults
	 *
	 * @return $this
	 */
	protected function createAccount(string $handler, array $data = [])
	{
		$defaults = $this->generateAccount();

		foreach ($data as $field => $value)
		{
			$defaults->$field = $value;
		}

		$handler = self::$accounts->$handler;
		$account = $this->handler->add($defaults);

		$this->removeCache[] = [$handler, $account->uid()];

		return $account;
	}

	/**
	 * Removes an Account.
	 *
	 * @param string $handler  Handler name to request of the factory
	 * @param mixed $uid       The ID of the account to remove
	 *
	 * @return bool
	 */
	protected function removeAccount(string $handler, $uid): bool
	{
		$handler = self::$accounts->$handler;

		return $this->handler->remove($uid);
	}
}
