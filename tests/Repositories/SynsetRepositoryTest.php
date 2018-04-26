<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Repositories;

use AL\PhpWndb\DataStorage\SynsetDataLoaderInterface;
use AL\PhpWndb\Model\Synsets\SynsetFactoryInterface;
use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\Parsing\SynsetDataParserInterface;
use AL\PhpWndb\Repositories\SynsetRepository;
use AL\PhpWndb\Tests\BaseTestAbstract;

class SynsetRepositoryTest extends BaseTestAbstract
{
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

	public function testDispose(): void
	{
		$synsetOffset = 1000;
		$repository = $this->createRepository($synsetOffset);

		$synset1 = $repository->getSynset($synsetOffset);
		$repository->dispose($synset1);
		$synset2 = $repository->getSynset($synsetOffset);

		static::assertNotSame($synset1, $synset2);
	}


	protected function createRepository(?int $synsetOffset = null): SynsetRepository
	{
		$factory = $this->createMock(SynsetFactoryInterface::class);
		$factory->method('createSynsetFromParseData')->willReturnCallback(function () use ($synsetOffset) {
			$synset = $this->createMock(SynsetInterface::class);
			$synset->method('getSynsetOffset')->willReturn($synsetOffset);
			return $synset;
		});

		return new SynsetRepository(
			$this->createMock(SynsetDataLoaderInterface::class),
			$this->createMock(SynsetDataParserInterface::class),
			$factory
		);
	}
}
