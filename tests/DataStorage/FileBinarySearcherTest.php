<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\DataStorage;

use AL\PhpWndb\DataStorage\FileReader;
use AL\PhpWndb\DataStorage\FileBinarySearcher;
use AL\PhpWndb\Tests\BaseTestAbstract;
use InvalidArgumentException;

class FileBinarySearcherTest extends BaseTestAbstract
{
	/**
	 * @dataProvider dpTestSearchFor
	 */
	public function testSearchFor(string $searchedRecordPrefix, ?string $expectedResult): void
	{
		$searcher = $this->createSearcher();

		static::assertSame($expectedResult, $searcher->searchFor($searchedRecordPrefix));
	}

	/**
	 * @return array<array<?string>>
	 */
	public function dpTestSearchFor(): array
	{
		return [
			['1-dodecanol', '1-dodecanol n 1 1 @ 1 0 14954808  '],
			['attlee',      'attlee n 1 1 @ 1 0 10847477  '],
			['arda',        'arda n 1 2 @ #p 1 0 08357680  '],
			['aaaa',        null],
			['xxx',         null],
		];
	}


	public function testFindLemmaIndexDataEmptyInput(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Record prefix has to be non empty.');

		$searcher = $this->createSearcher();
		$searcher->searchFor('');
	}


	protected function createSearcher(): FileBinarySearcher
	{
		$reader = new FileReader(__DIR__ . '/FileBinarySearcherTest.data');
		return new FileBinarySearcher($reader, "\n", ' ', 4 * 1024);
	}
}
