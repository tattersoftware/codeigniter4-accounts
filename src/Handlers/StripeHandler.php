<?php namespace Tatter\Accounts\Handlers;

use Stripe\Customer;
use Stripe\Stripe;
use Tatter\Accounts\Entities\Account;


/**
 * Class StripeHandler
 *
 * A wrapper class to implement the Customer
 * endpoints of the Stripe PHP SDK.
 * https://stripe.com/docs/api/customers
 */
class StripeHandler extends BaseHandler
{
	const LIBRARY_VERSION = '1.0';

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
		'id'    => 'id',
		'name'  => 'name',
		'email' => 'email',
		'phone' => 'phone',
	];

	/**
	 * Toggle for debug mode - whether exceptions throw or not
	 *
	 * @var bool
	 */
	public $debug;

	/**
	 * Initiate the Stripe API with the given secret, defaults to the environment key.
	 *
	 * @param string $secret  Client secret from the Stripe Dashboard (https://dashboard.stripe.com/account/apikeys)
	 */
	public function __construct(string $secret = null)
	{
		if ($secret === null)
		{
			$secret = env('stripe.secret');
		}

		$this->debug = CI_DEBUG;

		// Initialize the API
		Stripe::setApiKey($secret);
		Stripe::setAppInfo('Tatter\Accounts', self::LIBRARY_VERSION, 'https://github.com/tattersoftware/codeigniter4-accounts');
	}

	//--------------------------------------------------------------------
	// Utilities
	//--------------------------------------------------------------------

	/**
	 * Wrap original source data into an Account based on $fields.
	 *
	 * @param Customer $customer  Response object from the SDK
	 *
	 * @return Account
	 */
	protected function wrap($customer): Account
	{
		// Create the account entity
		$account = new Account(self::class, $customer->{$this->primaryKey} ?? null);

		// Map each field
		foreach ($this->fields as $from => $to)
		{
			if (isset($customer->$from))
			{
				$account->$to = $customer->$from;
			}
		}

		// Inject the original response
		$account->original($customer);

		return $account;
	}

	/**
	 * Common try..catch wrapper for SDK calls.
	 *
	 * @param callable $callback  The static SDK method
	 * @param mixed $params       Parameters to pass to the callable
	 *
	 * @return mixed|null
	 */
	protected function tryStripeMethod(callable $callback, ...$params)
	{
		// If debug mode is enabled then make the call directly
		if ($this->debug)
		{
			return $callback(...$params);
		}

		// Otherwise intercept errors
		try
		{
			$result = $callback(...$params);
		}
		catch (\Exception $e)
		{
			 $this->errors[] = $e->getMessage();
			 return null;
		}

		return $result;
	}

	//--------------------------------------------------------------------
	// CRUD
	//--------------------------------------------------------------------

	/**
	 * Return an Account by its UID
	 *
	 * @param mixed $uid  The value of primaryKey to look for
	 *
	 * @return Account|null
	 */
	public function get($uid): ?Account
	{
		if (! $customer = $this->tryStripeMethod(['\Stripe\Customer', 'retrieve'], $uid))
		{
			return null;
		}

		// Wrap the result into an Account
		return $this->wrap($customer);
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

		// Try to create the Customer
		if (! $customer = $this->tryStripeMethod(['\Stripe\Customer', 'create'], $data))
		{
			return null;
		}

		// Return the new entity as an Account
		return $this->wrap($customer);
	}

	/**
	 * Update an existing account
	 *
	 * @param mixed $uid   The value of primaryKey to look for
	 * @param mixed Account|array  Values to use
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

		// Try to update it
		if (! $customer = $this->tryStripeMethod(['\Stripe\Customer', 'update'], $uid, $data))
		{
			return false;
		}

		return true;
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
		if (! $customer = $this->get($uid))
		{
			return false;
		}

		$customer->original()->delete();

		return $customer->original()->deleted;
	}
}
