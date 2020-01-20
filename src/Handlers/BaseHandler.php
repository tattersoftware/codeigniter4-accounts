<?php namespace Tatter\Accounts;

use Tatter\Accounts\Interfaces\AccountInterface;

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
	 * Return an account by its primary key
	 *
	 * @param mixed $id  The value of primaryKey to look for
	 *
	 * @return Account|null
	 */
	abstract public function find($id): ?Account;

	/**
	 * Map source values to their internal version
	 *
	 * @param mixed $raw  Raw entity from the source
	 *
	 * @return Account
	 */
	abstract protected function map($raw): Account;

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
}
