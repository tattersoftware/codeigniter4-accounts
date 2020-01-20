<?php namespace Tatter\Accounts\Entities\Account;

/**
 * Class Account
 *
 * A simple entity structure for standardized account returns.
 */
class Account
{
	//--------------------------------------------------------------------
	// Public Data
	//--------------------------------------------------------------------

	/**
	 * ID, like from a database
	 *
	 * @var int|string|null
	 */
	public $id;

	/**
	 * Full name
	 *
	 * @var string|null
	 */
	public $name;

	/**
	 * Valid email address
	 *
	 * @var string|null
	 */
	public $email;

	/**
	 * Username
	 *
	 * @var string|null
	 */
	public $username;

	/**
	 * Whether the handler considers this account valid & usable
	 *
	 * @var bool|null
	 */
	public $valid;

	//--------------------------------------------------------------------
	// Utilities
	//--------------------------------------------------------------------

	/**
	 * Name of the source handler
	 *
	 * @var string
	 */
	protected $handler;

	/**
	 * Original data returned from the source
	 *
	 * @var mixed
	 */
	protected $original;

	/**
	 * Create a new entity noting the source handler
	 *
	 * @param string $handler  Name of the source handler
	 */
	public function __construct(string $handler)
	{
		$this->handler = $handler;
	}

	/**
	 * Sets or returns original set of data
	 *
	 * @param mixed|null $data  Original returned data, or null to fetch
	 *
	 * @return mixed
	 */
	protected function original($data = null)
	{
		if ($data !== null)
		{
			$this->original = $data;
		}

		return $this->original;
	}
}
