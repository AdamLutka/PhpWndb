<?php
declare(strict_types = 1);

namespace AL\PhpWndb\Tests\DataStorage;

use AL\PhpWndb\DataStorage\WholeSynsetDataLoader;
use AL\PhpWndb\Tests\AbstractBaseTest;

class WholeSynsetDataLoaderTest extends AbstractBaseTest
{
	public function testGetSynsetData(): void
	{
		$loader = $this->createLoader('WholeSynsetDataLoaderTest.ok');

		static::assertSame(
			'00000030 03 n 01 entity 0 003 ~ 00001930 n 0000 ~ 00002137 n 0000 ~ 04431553 n 0000 | that which is perceived or known or inferred to have its own distinct existence (living or nonliving)',
			$loader->getSynsetData(30)
		);

		static::assertSame(
			'00000218 03 n 01 physical_entity 0 007 @ 00001740 n 0000 ~ 00002452 n 0000 ~ 00002684 n 0000 ~ 00007347 n 0000 ~ 00021007 n 0000 ~ 00029976 n 0000 ~ 14604577 n 0000 | an entity that has physical existence',
			$loader->getSynsetData(218)
		);
	}

	/**
	 * @expectedException \AL\PhpWndb\DataStorage\Exceptions\UnknownSynsetOffsetException
	 */
	public function testGetSynsetDataUnknowSynsetOffset(): void
	{
		$loader = $this->createLoader('WholeSynsetDataLoaderTest.ok');
		$loader->getSynsetData(1000);
	}

	/**
	 * @expectedException \AL\PhpWndb\Exceptions\InvalidStateException
	 */
	public function testGetSynsetDataDuplicitOffset(): void
	{
		$loader = $this->createLoader('WholeSynsetDataLoaderTest.duplicity');
		$loader->getSynsetData(30);
	}

	/**
	 * @expectedException \AL\PhpWndb\Exceptions\IOException
	 */
	public function testGetSynsetDataFileNotFound(): void
	{
		$loader = $this->createLoader('WholeSynsetDataLoaderTest.notExist');
		$loader->getSynsetData(30);
	}


	private function createLoader(string $fixtureFileName): WholeSynsetDataLoader
	{
		return new WholeSynsetDataLoader(__DIR__ . '/_fixtures/' . $fixtureFileName);
	}
}
