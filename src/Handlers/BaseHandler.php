<?php namespace Tatter\Accounts\Handlers;

use Tatter\Accounts\Entities\Account;

abstract class BaseHandler
{
	/**
	 * Instance of the class to use.
	 *
	 * @var mixed
	 */
	protected $source;

	/**
	 * Source field to use as the unique identifier.
	 *
	 * @var string
	 */
	protected $primaryKey;

	/**
	 * Internal fields supported by this handler.
	 * Defines the mapping of values, $original => $internal.
	 *
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Error messages from the last call
	 *
	 * @var array
	 */
	protected $errors = [];

	/**
	 * Change the source instance.
	 *
	 * @param mixed $source  Instance of the source class
	 *
	 * @return $this
	 */
	public function setSource($source): self
	{
		$this->source = $source;

		return $this;
	}

	/**
	 * Change the primary key used for lookups.
	 *
	 * @param string $field  Name of the field to use
	 *
	 * @return $this
	 */
	public function setPrimaryKey(string $field): self
	{
		$this->primaryKey = $field;

		return $this;
	}

	/**
	 * Get and clear any error messsages
	 *
	 * @return array  Any error messages from the last call
	 */
	public function errors(): array
	{
		$errors       = $this->errors;
		$this->errors = [];

		return $errors;
	}

	/**
	 * Use $fields to create an array of data from $account that is ready for add/update
	 *
	 * @param Account $account  Any Account object
	 *
	 * @return array  Data in a handler-specific format, e.g. ready to be used with add()
	 */
	protected function unwrap(Account $account): array
	{
		$data = [];

		// Check each field
		foreach ($this->fields as $to => $from)
		{
			// Never include the primary key
			if ($from == $this->primaryKey)
			{
				continue;
			}

			if (isset($account->$from))
			{
				$data[$to] = $account->$from;
			}
		}

		return $data;
	}

	/**
	 * Create an Account from source data
	 *
	 * @param mixed $data  Original result from the source
	 *
	 * @return Account
	 */
	abstract protected function wrap($data): Account;

	/**
	 * Return an account by its primary key
	 *
	 * @param mixed $uid  The value of primaryKey to look for
	 *
	 * @return Account|null
	 */
	abstract public function get($uid): ?Account;

	/**
	 * Create a new account and return it
	 *
	 * @param Account|array|object $data  Values to use
	 *
	 * @return Account|null
	 */
	abstract public function add($data): ?Account;

	/**
	 * Update an existing account
	 *
	 * @param mixed $uid   The value of primaryKey to update
	 * @param Account|array|object $data  Values to use
	 *
	 * @return bool
	 */
	abstract public function update($uid, $data): bool;

	/**
	 * Deletes a single account where $uid matches the primaryKey
	 *
	 * @param mixed $uid  The account's primary key
	 *
	 * @return bool
	 */
	abstract public function remove($uid): bool;
}
