<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Repositories;

use AL\PhpWndb\DataMapping\LemmaMapperInterface;
use AL\PhpWndb\DataStorage\WordIndexLoaderInterface;
use AL\PhpWndb\Model\Indexes\WordIndexFactoryInterface;
use AL\PhpWndb\Model\Indexes\WordIndexInterface;
use AL\PhpWndb\Parsing\WordIndexParserInterface;
use AL\PhpWndb\Repositories\WordIndexRepository;
use AL\PhpWndb\Tests\BaseTestAbstract;

class WordIndexRepositoryTest extends BaseTestAbstract
{
	public function testFindWordIndexCacheSame(): void
	{
		$repository = $this->createRepository();

		$lemma = 'car';
		$wordIndex1 = $repository->findWordIndex($lemma);
		$wordIndex2 = $repository->findWordIndex($lemma);

		static::assertSame($wordIndex1, $wordIndex2);
	}

	public function testFindWordIndexCacheDifferent(): void
	{
		$repository = $this->createRepository();

		$wordIndex1 = $repository->findWordIndex('car');
		$wordIndex2 = $repository->findWordIndex('lorry');

		static::assertNotSame($wordIndex1, $wordIndex2);
	}

	public function testDispose(): void
	{
		$lemma = 'car';
		$repository = $this->createRepository($lemma);

		$wordIndex1 = $repository->findWordIndex($lemma);

		static::assertNotNull($wordIndex1);

		$repository->dispose($wordIndex1);
		$wordIndex2 = $repository->findWordIndex($lemma);

		static::assertNotNull($wordIndex2);
		static::assertNotSame($wordIndex1, $wordIndex2);
	}


	protected function createRepository(string $lemma = ''): WordIndexRepository
	{
		$lemmaMapper = $this->createMock(LemmaMapperInterface::class);
		$lemmaMapper->method('lemmaToToken')->willReturnCallback(function ($lemma) {
			return $lemma;
		});

		$loader = $this->createMock(WordIndexLoaderInterface::class);
		$loader->method('findLemmaIndexData')->willReturnCallback(function () {
			return '...';
		});

		$parser = $this->createMock(WordIndexParserInterface::class);

		$factory = $this->createMock(WordIndexFactoryInterface::class);
		$factory->method('createWordIndexFromParsedData')->willReturnCallback(function () use ($lemma) {
			$wordIndex = $this->createMock(WordIndexInterface::class);
			$wordIndex->method('getLemma')->willReturn($lemma);

			return $wordIndex;
		});

		return new WordIndexRepository($lemmaMapper, $loader, $parser, $factory);
	}
}
