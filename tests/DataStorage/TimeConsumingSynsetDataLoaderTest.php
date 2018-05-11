<?php
declare(strict_types = 1);

namespace AL\PhpWndb\Tests\DataStorage;

use AL\PhpWndb\DataStorage\FileReaderInterface;
use AL\PhpWndb\DataStorage\TimeConsumingSynsetDataLoader;
use AL\PhpWndb\Tests\BaseTestAbstract;

class TimeConsumingSynsetDataLoaderTest extends BaseTestAbstract
{
	public function testFindSynsetData(): void
	{
		$loader = $this->createLoader('00000030 03 n 01 entity 0 003 ~ 00001930 n 0000 ~ 00002137 n 0000 ~ 04431553 n 0000 | that which is perceived or known or inferred to have its own distinct existence (living or nonliving)');

		static::assertSame(
			'00000030 03 n 01 entity 0 003 ~ 00001930 n 0000 ~ 00002137 n 0000 ~ 04431553 n 0000 | that which is perceived or known or inferred to have its own distinct existence (living or nonliving)',
			$loader->findSynsetData(30)
		);
	}

	public function testFindSynsetDataUnknowSynsetOffset(): void
	{
		$loader = $this->createLoader('entity 0 003 ~ 00001930 n 0000 ~ 00002137 n 0000 ~ 04431553 n 0000 | that which is perceived or known or inferred to have its own distinct existence (living or nonliving)');
		static::assertNull($loader->findSynsetData(1000));
	}


	public function testGetSynsetData(): void
	{
		$loader = $this->createLoader('00000030 03 n 01 entity 0 003 ~ 00001930 n 0000 ~ 00002137 n 0000 ~ 04431553 n 0000 | that which is perceived or known or inferred to have its own distinct existence (living or nonliving)');

		static::assertSame(
			'00000030 03 n 01 entity 0 003 ~ 00001930 n 0000 ~ 00002137 n 0000 ~ 04431553 n 0000 | that which is perceived or known or inferred to have its own distinct existence (living or nonliving)',
			$loader->findSynsetData(30)
		);
	}

	/**
	 * @expectedException \AL\PhpWndb\Exceptions\UnknownSynsetOffsetException
	 */
	public function testGetSynsetDataUnknowSynsetOffset(): void
	{
		$loader = $this->createLoader('entity 0 003 ~ 00001930 n 0000 ~ 00002137 n 0000 ~ 04431553 n 0000 | that which is perceived or known or inferred to have its own distinct existence (living or nonliving)');
		$loader->getSynsetData(1000);
	}


	protected function createLoader(string $blockData): TimeConsumingSynsetDataLoader
	{
		$loader = $this->createMock(FileReaderInterface::class);
		$loader->method('readBlock')->willReturn($blockData . "\n some other data");

		return new TimeConsumingSynsetDataLoader($loader);
	}
}
