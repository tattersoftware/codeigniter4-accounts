<?php namespace Tatter\Accounts\Handlers;

use Tatter\Accounts\Entities\Account;

abstract class BaseHandler
{
	/**
	 * Target field to use as the unique identifier.
	 *
	 * @var string
	 */
	protected $primaryKey;

	/**
	 * Internal fields supported by this handler.
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
	public function getErrors(): array
	{
		$errors       = $this->errors;
		$this->errors = [];

		return $errors;
	}

	/**
	 * Map source values to their internal version
	 *
	 * @param mixed $data  Original result from the source
	 *
	 * @return Account
	 */
	abstract protected function map($data): Account;

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
	 * @param mixed $data  Values to use
	 *
	 * @return Account|null
	 */
	abstract public function add($data): ?Account;

	/**
	 * Update an existing account
	 *
	 * @param mixed $uid   The value of primaryKey to look for
	 * @param mixed $data  Values to use
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
