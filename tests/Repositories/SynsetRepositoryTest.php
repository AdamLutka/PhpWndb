<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Repositories;

use AL\PhpWndb\DataStorage\SynsetDataLoaderInterface;
use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;
use AL\PhpWndb\Model\Synsets\SynsetFactoryInterface;
use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\Parsing\SynsetDataParserInterface;
use AL\PhpWndb\Repositories\SynsetRepository;
use AL\PhpWndb\Tests\BaseTestAbstract;

class SynsetRepositoryTest extends BaseTestAbstract
{
	public function testFindSynsetCacheSame(): void
	{
		$repository = $this->createRepository();

		$synsetOffset = 1000;
		$synset1 = $repository->findSynset($synsetOffset);
		$synset2 = $repository->findSynset($synsetOffset);

		static::assertNotNull($synset1);
		static::assertNotNull($synset2);
		static::assertSame($synset1, $synset2);
	}

	public function testFindSynsetCacheDifferent(): void
	{
		$repository = $this->createRepository();

		$synset1 = $repository->findSynset(100);
		$synset2 = $repository->findSynset(101);

		static::assertNotNull($synset1);
		static::assertNotNull($synset2);
		static::assertNotSame($synset1, $synset2);
	}

	public function testFindSynsetUnknown(): void
	{
		$repository = $this->createRepository();
		$synset = $repository->findSynset(-100);

		static::assertNull($synset);
	}


	public function testGetSynsetCacheSame(): void
	{
		$repository = $this->createRepository();

		$synsetOffset = 1000;
		$synset1 = $repository->getSynset($synsetOffset);
		$synset2 = $repository->getSynset($synsetOffset);

		static::assertSame($synset1, $synset2);
	}

	public function testGetSynsetCacheDifferent(): void
	{
		$repository = $this->createRepository();

		$synset1 = $repository->getSynset(100);
		$synset2 = $repository->getSynset(101);

		static::assertNotSame($synset1, $synset2);
	}

	public function testGetSynsetUnknown(): void
	{
		$this->expectException(UnknownSynsetOffsetException::class);

		$repository = $this->createRepository();
		$synset = $repository->getSynset(-100);
	}


	public function testDispose(): void
	{
		$synsetOffset = 1000;
		$repository = $this->createRepository();

		$synset1 = $repository->getSynset($synsetOffset);
		$repository->dispose($synset1);
		$synset2 = $repository->getSynset($synsetOffset);

		static::assertNotSame($synset1, $synset2);
	}


	protected function createRepository(): SynsetRepository
	{
		$synsetOffset = null;

		$loader = $this->createMock(SynsetDataLoaderInterface::class);
		$loader->method('findSynsetData')->willReturnCallback(function ($offset) use (&$synsetOffset){
			$synsetOffset = $offset;
			return $offset > 0 ? '...' : null;
		});

		$factory = $this->createMock(SynsetFactoryInterface::class);
		$factory->method('createSynsetFromParsedData')->willReturnCallback(function () use (&$synsetOffset) {
			$synset = $this->createMock(SynsetInterface::class);
			$synset->method('getSynsetOffset')->willReturn($synsetOffset);
			return $synset;
		});

		return new SynsetRepository(
			$loader,
			$this->createMock(SynsetDataParserInterface::class),
			$factory
		);
	}
}
