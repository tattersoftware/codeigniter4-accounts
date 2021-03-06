<?php namespace Tatter\Accounts\Entities;

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
	 * Username
	 *
	 * @var string|null
	 */
	public $username;

	/**
	 * Valid email address
	 *
	 * @var string|null
	 */
	public $email;

	/**
	 * Valid phone number
	 *
	 * @var string|null
	 */
	public $phone;

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
	 * @var int|string|null
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
	 * @param int|string $uid  This account's unique identifier; null should indicate an un-created account
	 */
	public function __construct(string $handler, $uid = null)
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

	/**
	 * Split a full name into $num segments
	 * Extra names go into the first segment
	 *
	 * @param int $num  Number of segments, e.g. 2 = [first,last]
	 *
	 * @return ?array $names
	 */
	public function names(int $num = 2): ?array
	{
		if ($this->name === null)
		{
			return null;
		}

		$names  = explode(' ', $this->name);
		$return = [];
		
		for ($i = 1; $i < $num; $i++)
		{
			if (! empty($names))
			{
				$return[] = array_pop($names);
			}
		}

		if (! empty($names))
		{
			$return[] = implode(' ', $names);
		}
		
		return array_reverse($return);
	}
}
