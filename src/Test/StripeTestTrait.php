<?php namespace Tatter\Accounts\Test;

use Tatter\Accounts\Handlers\StripeHandler;

trait StripeTestTrait
{
	use AccountsTestTrait;

	/**
	 * Creates a Stripe customer on-the-fly.
	 *
	 * @param array $data
	 *
	 * @return $this
	 */
	protected function createStripeAccount(array $data = [])
	{
		// If no Stripe object is available then get one
		if (empty($this->handler))
		{
			$this->handler = new StripeHandler();
		}

		$defaults = $this->generateAccount();

		foreach ($data as $field => $value)
		{
			$defaults->$field = $value;
		}

		$account = $this->handler->add($defaults);
		$this->removeCache[] = ['StripeAccount', $account->uid()];

		return $account;
	}

	/**
	 * Removes a Stripe customer.
	 *
	 * @param string $uid  The ID of the Customer to remove
	 *
	 * @return bool
	 */
	protected function removeStripeAccount(string $uid): bool
	{
		// If no Stripe object is available then get one
		if (empty($this->handler))
		{
			$this->handler = new StripeHandler();
		}

		return $this->handler->remove($uid);
	}
}
