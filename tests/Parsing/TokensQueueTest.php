<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Parsing;

use AL\PhpWndb\Parsing\TokensQueue;
use AL\PhpWndb\Tests\BaseTestAbstract;
use InvalidArgumentException;

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

	public function testEmptyQueue(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('No tokens left.');

		$queue = new TokensQueue([]);
		$queue->takeOutString();
	}

	public function testInvalidDecInt(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Decimal integer expected:');

		$queue = new TokensQueue(['foo']);
		$queue->takeOutDecInt();
	}

	public function testInvalidHexInt(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Hexadecimal integer expected:');

		$queue = new TokensQueue(['foo']);
		$queue->takeOutHexInt();
	}
}
