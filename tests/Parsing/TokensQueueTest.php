<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Parsing;

use AL\PhpWndb\Parsing\TokensQueue;
use AL\PhpWndb\Tests\BaseTestAbstract;

class TokensQueueTest extends BaseTestAbstract
{
	public function testQueue(): void
	{
		$queue = new TokensQueue(['a', '55', 'string', 'a0b3']);

		static::assertSame(4, $queue->getCount());
		static::assertSame(10, $queue->takeOutHexInt());
		static::assertSame(55, $queue->takeOutDecInt());
		static::assertSame('string', $queue->takeOutString());
		static::assertSame([160, 179], $queue->takeOutHexIntPair());
		static::assertSame(0, $queue->getCount());
	}

	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage No tokens left.
	 */
	public function testEmptyQueue(): void
	{
		$queue = new TokensQueue([]);
		$queue->takeOutString();
	}

	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Decimal integer expected:
	 */
	public function testInvalidDecInt(): void
	{
		$queue = new TokensQueue(['foo']);
		$queue->takeOutDecInt();
	}

	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Hexadecimal integer expected:
	 */
	public function testInvalidHexInt(): void
	{
		$queue = new TokensQueue(['foo']);
		$queue->takeOutHexInt();
	}
}
