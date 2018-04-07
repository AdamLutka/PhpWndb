<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Fixtures;

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractFixtures
{
	/** @var TestCase */
	private $testCase;


	public function __construct(TestCase $testCase)
	{
		$this->testCase = $testCase;
	}


	protected function createMock(string $originalClassName): MockObject
	{
		$builder = new MockBuilder($this->testCase, $originalClassName);
		return $builder
			->disableOriginalConstructor()
			->disableOriginalClone()
			->disableArgumentCloning()
			->disallowMockingUnknownTypes()
			->getMock();
	}
}
