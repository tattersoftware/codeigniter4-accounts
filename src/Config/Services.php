<?php namespace Tatter\Accounts\Config;

use CodeIgniter\Config\BaseService;
use Tatter\Accounts\Accounts;

class Services extends BaseService
{
	/**
	 * Returns an instance of the Accounts factory
	 *
	 * @param boolean  $getShared
	 *
	 * @return \Tatter\Accounts\Accounts
	 */
	public static function accounts(bool $getShared = true): Accounts
	{
		if ($getShared)
		{
			return static::getSharedInstance('accounts');
		}

		return new Accounts();
	}
}
