<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\DataStorage;

use AL\PhpWndb\DataStorage\FileReader;
use AL\PhpWndb\DataStorage\TimeConsumingWordIndexLoader;
use AL\PhpWndb\Tests\BaseTestAbstract;

class TimeConsumingWordIndexLoaderTest extends BaseTestAbstract
{
	/**
	 * @dataProvider dpTestFindLemmaIndexData
	 */
	public function testFindLemmaIndexData(string $searchedLemmaToken, ?string $expectedResult): void
	{
		$loader = $this->createLoader();

		static::assertSame($expectedResult, $loader->findLemmaIndexData($searchedLemmaToken));
	}

	public function dpTestFindLemmaIndexData(): array
	{
		return [
			['1-dodecanol', '1-dodecanol n 1 1 @ 1 0 14954808  '],
			['attlee',      'attlee n 1 1 @ 1 0 10847477  '],
			['arda',        'arda n 1 2 @ #p 1 0 08357680  '],
			['xxx',         null],
		];
	}


	/**
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Lemma token has to be non empty.
	 */
	public function testFindLemmaIndexDataEmptyInput(): void
	{
		$loader = $this->createLoader();
		$loader->findLemmaIndexData('');
	}


	protected function createLoader(): TimeConsumingWordIndexLoader
	{
		return new TimeConsumingWordIndexLoader(new FileReader(__DIR__ . '/TimeConsumingWordIndexLoaderTest.data'));
	}
}
