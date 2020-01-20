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
	 * This account's unique identifier
	 * Corresponds to the handler's primaryKey
	 *
	 * @var int|string
	 */
	protected $uid;

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
	 * @param int|string $uid  This account's unique identifier
	 */
	public function __construct(string $handler, $uid)
	{
		$this->handler = $handler;
		$this->uid     = $uid;
	}

	/**
	 * Returns this account's unique identifier
	 *
	 * @return mixed
	 */
	public function uid()
	{
		return $this->uid;
	}

	/**
	 * Sets or returns the original set of data
	 *
	 * @param mixed|null $data  Original returned data, or null to fetch
	 *
	 * @return mixed
	 */
	public function original($data = null)
	{
		if ($data !== null)
		{
			$this->original = $data;
		}

		return $this->original;
	}
}
