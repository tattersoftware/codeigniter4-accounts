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
	protected function map($data, Account $account): Account
	{
		$account = new Account(self::class);

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

		// Map each field
		foreach ($this->fields as $from => $to)
		{
			$account->$to = $data[$from];
		}

		return $account;
	}

	/**
	 * Return an account by its ID
	 *
	 * @param mixed $id  The value of primaryKey to look for
	 *
	 * @return Account|null
	 */
	public function find($id): ?Account
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
