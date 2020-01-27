<?php namespace ModuleTests\Support\Accounts;

use Tatter\Accounts\Entities\Account;
use Tatter\Accounts\Handlers\BaseHandler;

class DummyHandler extends BaseHandler
{
	public function __construct(string $secret = null)
	{
		$this->account = new Account(self::class, 1);
	}

	protected function wrap($data): Account
	{
		return $this->account;
	}

	public function get($uid): ?Account
	{
		return $this->account;
	}

	public function add($data): ?Account
	{
		return $this->account;
	}

	public function update($uid, $data): bool
	{
		return true;
	}

	public function remove($uid): bool
	{
		return true;
	}
}
