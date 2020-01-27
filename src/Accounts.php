<?php namespace Tatter\Accounts;

use Tatter\Accounts\Handlers\BaseHandler;

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
	 * Set a handler to a nickname
	 *
	 * @param string $name
	 * @param BaseHandler $handler  Instance of the handler
	 *
	 * @return $this
	 */
	public function setHandler(string $name, BaseHandler $handler): self
	{
		$name = lcfirst($name);
		$this->instances[$name] = $handler;

		return $this;
	}
	
	/**
	 * Get the handler instance loaded to a nickname
	 * Mostly used for testing, use the magic function instead
	 *
	 * @param string $name
	 *
	 * @return BaseHandler|null
	 */
	public function getHandler(string $name): ?BaseHandler
	{
		$name = lcfirst($name);
		return $this->instances[$name] ?? null;
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

		// Check App first
		$className = '\App\Accounts\\' . ucfirst($name) . 'Handler';
		if (class_exists($className))
		{
			$this->instances[$name] = new $className();
			return $this->instances[$name];
		}

		// Check for an internal component
		$className = '\Tatter\Accounts\Handlers\\' . ucfirst($name) . 'Handler';
		if (class_exists($className))
		{
			$this->instances[$name] = new $className();
			return $this->instances[$name];
		}
		
		// Search all namespaces
		$locator = service('locator');
		$files = $locator->search('Accounts/' . ucfirst($name) . 'Handler');

		if (! empty($files))
		{
			$file = reset($files);
			$className = $locator->getClassname($file);

			$this->instances[$name] = new $className();
			return $this->instances[$name];
		}

		throw new \RuntimeException("Handler {$name} does not exist");
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
