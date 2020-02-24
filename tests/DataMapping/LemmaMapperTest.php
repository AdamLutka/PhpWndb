<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\DataMapping;

use AL\PhpWndb\Cache\CacheInterface;
use AL\PhpWndb\DataMapping\LemmaMapper;
use AL\PhpWndb\Tests\BaseTestAbstract;

class LemmaMapperTest extends BaseTestAbstract
{
	/**
	 * @dataProvider dpTestLemmaToToken
	 */
	public function testLemmaToToken(string $expectedToken, string $lemma): void
	{
		$mapper = $this->createLemmaMapper();

		static::assertSame($expectedToken, $mapper->lemmaToToken($lemma));
	}

	/**
	 * @return array<array<string>>
	 */
	public function dpTestLemmaToToken(): array
	{
		return [
			['', ''],
			['test', 'test'],
			['a_b_c', 'a B c'],
			['123', '123'],
		];
	}


	/**
	 * @dataProvider dpTestTokenToLemma
	 */
	public function testTokenToLemma(string $expectedLemma, string $token): void
	{
		$mapper = $this->createLemmaMapper();

		static::assertSame($expectedLemma, $mapper->tokenToLemma($token));
	}

	/**
	 * @return array<array<string>>
	 */
	public function dpTestTokenToLemma(): array
	{
		return [
			['', ''],
			['test', 'test'],
			['a b c', 'a_b_c'],
			['123', '123'],
		];
	}


	private function createLemmaMapper(): LemmaMapper
	{
		return new LemmaMapper($this->createMock(CacheInterface::class));
	}
}
