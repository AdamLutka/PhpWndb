<?php
declare(strict_types = 1);

namespace AL\PhpWndb\Tests\DataStorage;

use AL\PhpWndb\DataStorage\FileReaderInterface;
use AL\PhpWndb\DataStorage\MemoryConsumingWordIndexLoader;
use AL\PhpWndb\Tests\BaseTestAbstract;

class MemoryConsumingWordIndexLoaderTest extends BaseTestAbstract
{
	public function testFindLemmaIndexData(): void
	{
		$loader = $this->createLoader([
			'  1 This line is ignored',
			'  2 and this too',
			'',
			'\'hood n 1 2 @ ; 1 0 08659519  ',
			'.22 n 1 2 @ ~ 1 0 04510146  ',
		]);

		static::assertSame(
			'\'hood n 1 2 @ ; 1 0 08659519  ',
			$loader->findLemmaIndexData('\'hood')
		);

		static::assertSame(
			'.22 n 1 2 @ ~ 1 0 04510146  ',
			$loader->findLemmaIndexData('.22')
		);
	}

	public function testFindLemmaIndexDataUnknowLemma(): void
	{
		$loader = $this->createLoader([
			'abysm n 1 2 @ + 1 0 09209256  ',
		]);
		static::assertNull($loader->findLemmaIndexData('not exist'));
	}

	/**
	 * @expectedException \AL\PhpWndb\Exceptions\InvalidStateException
	 */
	public function testFindLemmaIndexDataDuplicitLemma(): void
	{
		$loader = $this->createLoader([
			'abysm n 1 2 @ + 1 0 09209256  ',
			'abyss n 1 2 @ + 1 1 09209256  ',
			'abysm n 1 2 @ + 1 0 09209256  ',
		]);
		$loader->findLemmaIndexData('abyss');
	}


	private function createLoader(array $data): MemoryConsumingWordIndexLoader
	{
		$reader = $this->createMock(FileReaderInterface::class);
		$reader->method('readAll')->willReturn($data);

		return new MemoryConsumingWordIndexLoader($reader);
	}
}
