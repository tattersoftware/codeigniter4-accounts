<?php namespace Tatter\Accounts\Handlers;

use CodeIgniter\Model;
use Myth\Auth\Models\UserModel;

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
		$this->model = $model ?? new UserModel();
	}
}
