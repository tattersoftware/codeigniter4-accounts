<?php namespace Tatter\Accounts\Test;

use Tatter\Accounts\Entities\Account;

trait AccountsTestTrait
{
	/**
	 * Faker instance for generating content.
	 *
	 * @var Faker\Factory
	 */
	protected static $faker;

	/**
	 * Cache of objects to be removed during tearDown.
	 * Names correspond to the method "removeName()"
	 *
	 * @var array of [name, ID]
	 */
	protected $removeCache = [];

    /**
     * Initialize Faker and make sure the remove cache is clean
     */
	protected function accountsSetUp(): void
	{
		// Load Faker if it isn't already
		if (self::$faker == null)
		{
			self::$faker = \Faker\Factory::create();
		}

		$this->removeCache = [];
	}

	protected function accountsTearDown(): void
	{
		// Remove any test items in the cache
		foreach ($this->removeCache as $row)
		{
			$method = 'remove' . $row[0];
			$this->$method($row[1]);
		}
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
}
