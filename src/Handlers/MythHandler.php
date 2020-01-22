<?php namespace Tatter\Accounts\Handlers;

use CodeIgniter\Model;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;
use Tatter\Accounts\Entities\Account;

class MythHandler extends ModelHandler
{
	/**
	 * Target field to use as the unique identifier.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * Internal fields supported by this handler.
	 *
	 * @var array
	 */
	protected $fields = [
		'id'       => 'id',
		'email'    => 'email',
		'username' => 'username',
		'active'   => 'valid',
	];

	/**
	 * Load or store the model
	 *
	 * @param Model $model  Instance of the model, or null to load Myth's UserModel
	 */
	public function __construct(Model $model = null)
	{
		$this->source = $model ?? new UserModel();
	}

	/**
	 * Create a new account and return it.
	 * Runs arrays through the entity to apply setters.
	 *
	 * @param Account|array $data  Values to use
	 *
	 * @return Account|null
	 */
	public function add($data): ?Account
	{
		// If it is an array then do some prep
		if (is_array($data))
		{
			// If no password was provided then generate a random one so the account is usable
			if (! isset($data['password']))
			{
				$data['password'] = bin2hex(random_bytes(16));
			}

			// Run it through the entity to apply defaults, casts, and setters
			$data = (new User($data))->toRawArray();
		}

		return parent::add($data);
	}

	/**
	 * Update an existing account.
	 * Runs arrays through the entity to apply setters.
	 *
	 * @param mixed $uid   The value of primaryKey to look for
	 * @param mixed $Account|array  Values to use
	 *
	 * @return bool
	 */
	public function update($uid, $data): bool
	{
		// If it is an array then do some prep
		if (is_array($data))
		{
			// Run it through the entity to apply defaults, casts, and setters
			$data = (new User($data))->toRawArray();
		}

		return parent::update($uid, $data);
	}
}
