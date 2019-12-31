<?php
declare(strict_types = 1);

namespace AL\PhpWndb\Tests;

use PHPUnit\Framework\TestCase;
use AL\PhpEnum\Enum;

abstract class BaseTestAbstract extends TestCase
{
	/**
	 * @param mixed $actualEnum
	 */
	public static function assertEnum(Enum $expectedEnum, $actualEnum, string $message = ''): void
	{
		static::assertInstanceOf(Enum::class, $actualEnum);
		static::assertSame(
			get_class($expectedEnum) . '::' . $expectedEnum,
			get_class($actualEnum) . '::' . $actualEnum,
			$message
		);
	}
}
