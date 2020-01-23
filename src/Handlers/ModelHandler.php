<?php namespace Tatter\Accounts\Handlers;

use CodeIgniter\Model;
use Tatter\Accounts\Entities\Account;

abstract class ModelHandler extends BaseHandler
{
	/**
	 * Load or store the model as this handler's source
	 *
	 * @param Model $model  Instance of the model
	 */
	public function __construct(Model $model)
	{
		$this->source     = $model;
		$this->primaryKey = $model->primaryKey;
	}

	//--------------------------------------------------------------------
	// Utilities
	//--------------------------------------------------------------------

	/**
	 * Wrap original source data into an Account based on $fields.
	 *
	 * @param mixed $data  Result from the model
	 *
	 * @return Account
	 */
	protected function wrap($data): Account
	{
		$original = $data;

		// Get it to an array
		if (is_object($data) && ! $data instanceof stdClass)
		{
			$data = $this->source::classToArray($data, $this->primaryKey, 'datetime', false);
		}

		// If it's still a stdClass, go ahead and convert
		if (is_object($data))
		{
			$data = (array) $data;
		}

		// Create the account entity
		$account = new Account(self::class, $data[$this->primaryKey] ?? null);

		// Map each field
		foreach ($this->fields as $from => $to)
		{
			if (isset($data[$from]))
			{
				$account->$to = $data[$from];
			}
		}

		// Inject the model result
		$account->original($original);

		return $account;
	}

	//--------------------------------------------------------------------
	// CRUD
	//--------------------------------------------------------------------

	/**
	 * Return an account by its UID
	 *
	 * @param mixed $uid  The value of primaryKey to look for
	 *
	 * @return Account|null
	 */
	public function get($uid): ?Account
	{
		if (! $data = $this->source->find($uid))
		{
			return null;			
		}

		// Wrap the result into an Account
		return $this->wrap($data);
	}

	/**
	 * Create a new account and return it
	 *
	 * @param Account|array $data  Values to use
	 *
	 * @return Account|null
	 */
	public function add($data): ?Account
	{
		// If an Account was given then unwrap it
		if ($data instanceof Account)
		{
			$data = $this->unwrap($data);
		}

		// Try to insert it
		if (! $uid = $this->source->insert($data, true))
		{
			$this->errors = $this->source->errors();

			return null;			
		}

		// Return the new entity as an Account
		return $this->get($uid);
	}

	/**
	 * Update an existing account
	 *
	 * @param mixed $uid   The value of primaryKey to look for
	 * @param mixed $Account|array  Values to use
	 *
	 * @return bool
	 */
	public function update($uid, $data): bool
	{
		// If an Account was given then unwrap it
		if ($data instanceof Account)
		{
			$data = $this->unwrap($data);
		}

		$result = $this->source->update($uid, $data);

		return (bool) $result;
	}

	/**
	 * Deletes a single account where $uid matches the primaryKey
	 *
	 * @param mixed $uid  The the account's primary key
	 *
	 * @return bool
	 */
	public function remove($uid): bool
	{
		$result = $this->source->delete($uid);

		return (bool) $result;
	}
}
