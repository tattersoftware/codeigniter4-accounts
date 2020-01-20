<?php namespace Tatter\Accounts;

use CodeIgniter\Model;
use Tatter\Accounts\Entities\Account;

abstract class ModelHandler extends BaseHandler
{
	/**
	 * Target field to use as the unique identifier.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * The model to use.
	 *
	 * @var Model
	 */
	protected $model;

	/**
	 * Load or store the model
	 *
	 * @param Model $model  Instance of the model
	 */
	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	//--------------------------------------------------------------------
	// Utilities
	//--------------------------------------------------------------------

	/**
	 * Change the model instance.
	 *
	 * @param Model $model  Instance of the model
	 *
	 * @return $this
	 */
	public function setModel(Model $model): self
	{
		$this->model = $model;

		return $this;
	}

	/**
	 * Generic solution to map $fields from source to internal keys.
	 *
	 * @param mixed $data  Result from the model
	 *
	 * @return Account
	 */
	protected function map($data): Account
	{
		// Get it to an array
		if (is_object($data) && ! $data instanceof stdClass)
		{
			$data = $this->model::classToArray($data, $this->primaryKey);
		}

		// If it's still a stdClass, go ahead and convert
		if (is_object($data))
		{
			$data = (array) $data;
		}

		// Create the account entity
		$account = new Account(self::class, $data[$this->primaryKey]);

		// Map each field
		foreach ($this->fields as $from => $to)
		{
			$account->$to = $data[$from];
		}

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
		if (! $data = $this->model->find($id))
		{
			return null;			
		}

		// Get an Account with mapped values
		$account = $this->map($data);

		// Inject the model result
		$account->original($data);

		return $account;
	}

	/**
	 * Create a new account and return it
	 *
	 * @param mixed $data  Values to use
	 *
	 * @return Account|null
	 */
	public function add($data): ?Account
	{
		if (! $id = $this->model->insert($data, true))
		{
			$this->errors = $this->model->errors();

			return null;			
		}

		return $this->get($id);
	}

	/**
	 * Update an existing account
	 *
	 * @param mixed $uid   The value of primaryKey to look for
	 * @param mixed $data  Values to use
	 *
	 * @return bool
	 */
	public function update($uid, $data): bool
	{
		$result = $this->model->update($uid, $data);

		return (bool) $result;
	}

	/**
	 * Deletes a single account where $id matches the primaryKey
	 *
	 * @param mixed $uid  The the account's primary key
	 *
	 * @return bool
	 */
	public function remove($uid): bool
	{
		$result = $this->model->delete($uid);

		return (bool) $result;
	}
}
