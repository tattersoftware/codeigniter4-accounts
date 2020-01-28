<?php

use Tatter\Accounts\Entities\Account;

class EntityTest extends \CodeIgniter\Test\CIUnitTestCase
{
	public function testNamesDefaultReturnsTwo()
	{
		$account = new Account(self::class, 1);
		$account->name = 'Wilfrid Gordon McDonald Partridge';
		
		$expected = [
			'Wilfrid Gordon McDonald',
			'Partridge',
		];
		
		$this->assertEquals($expected, $account->names());
	}

	public function testNamesMoreReturnsEach()
	{
		$account = new Account(self::class, 1);
		$account->name = 'Wilfrid Gordon McDonald Partridge';
		
		$expected = [
			'Wilfrid',
			'Gordon',
			'McDonald',
			'Partridge',
		];
		
		$this->assertEquals($expected, $account->names(10));
	}

	public function testNamesZeroReturnsOne()
	{
		$account = new Account(self::class, 1);
		$account->name = 'Wilfrid Gordon McDonald Partridge';
		
		$expected = ['Wilfrid Gordon McDonald Partridge'];
		
		$this->assertEquals($expected, $account->names(0));
	}

	public function testNamesOneReturnsOne()
	{
		$account = new Account(self::class, 1);
		$account->name = 'Wilfrid Gordon McDonald Partridge';
		
		$expected = ['Wilfrid Gordon McDonald Partridge'];
		
		$this->assertEquals($expected, $account->names(1));
	}
}
