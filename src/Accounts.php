<?php namespace Tatter\Accounts;

/**
 * Class Accounts
 *
 * Accounts factory for the account handlers.
 */
class Accounts
{
	/**
	 * Cache for handlers that have already been loaded.
	 *
	 * @var array
	 */
	protected $instances = [];
	
	/**
	 * Initiate the factory
	 *
	 * @param string  $customerId
	 */
	public function __construct()
	{

	}

	//--------------------------------------------------------------------
	// Magic Functions
	//--------------------------------------------------------------------

	/**
	 * Returns or creates an instance from the corresponding handler
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get(string $name)
	{
		$name = lcfirst($name);

		if (isset($this->instances[$name]))
		{
			return $this->instances[$name];
		}

		// Look for an internal component
		$className = 'Tatter\Accounts\Handlers\\' . ucfirst($name);
		if (class_exists($className))
		{
			return new $className();
		}

		throw new \Exception("Property {$name} does not exist");
	}

	/**
	 * Checks for the existence of an instance or its corresponding Factory method
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function __isset(string $name): bool
	{
		$name = lcfirst($name);

		if (isset($this->instances[$name]))
		{
			return true;
		}
		
		$className = 'Tatter\Accounts\Handlers\\' . ucfirst($name);

		return class_exists($className);
	}
}
