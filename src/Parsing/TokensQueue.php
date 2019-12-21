<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing;

use InvalidArgumentException;

class TokensQueue
{
	/** @var string[] */
	protected $tokens;


	/**
	 * @param string[] $tokens
	 */
	public function __construct(array $tokens)
	{
		$this->tokens = $tokens;
	}


	public function getCount(): int
	{
		return count($this->tokens);
	}

	/**
	 * @return string[]
	 */
	public function toArray(): array
	{
		return $this->tokens;
	}


	/**
	 * @throws InvalidArgumentException
	 */
	public function takeOutString(): string
	{
		if (empty($this->tokens)) {
			throw new InvalidArgumentException('No tokens left.');
		}

		return array_shift($this->tokens);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function takeOutDecInt(): int
	{
		$number = $this->takeOutString();

		if (!ctype_digit($number)) {
			throw new InvalidArgumentException("Decimal integer expected: $number");
		}

		return (int)$number;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function takeOutHexInt(): int
	{
		return $this->toHexInt($this->takeOutString());
	}

	/**
	 * @return int[]
	 * @throws InvalidArgumentException
	 */
	public function takeOutHexIntPair(): array
	{
		$pair = $this->takeOutString();

		if (strlen($pair) !== 4) {
			throw new InvalidArgumentException("Hexadecimal integer pair expected: $pair");
		}

		return [
			$this->toHexInt(substr($pair, 0, 2)),
			$this->toHexInt(substr($pair, 2, 2)),
		];
	}


	/**
	 * @throws InvalidArgumentException
	 */
	protected function toHexInt(string $token): int
	{
		if (!ctype_xdigit($token)) {
			throw new InvalidArgumentException("Hexadecimal integer expected: $token");
		}

		return (int)hexdec($token);
	}
}
