<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Model\Indices\Collections;

use AL\PhpWndb\Model\Indices\WordIndexInterface;
use AL\PhpWndb\Model\Indices\Collections\WordIndexCollection;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\WordIndexMultiRepositoryInterface;
use AL\PhpWndb\Tests\BaseTestAbstract;

class WordIndexCollectionTest extends BaseTestAbstract
{
	public function testGetAllWordIndices(): void
	{
		$lemma = 'lemmmmmmma';

		$repository = $this->createMock(WordIndexMultiRepositoryInterface::class);
		$repository->expects($this->exactly(4))
			->method('findWordIndexByPartOfSpeech')
			->with($this->anything(), $lemma)
			->willReturn(null);

		$collection = new WordIndexCollection($repository, $lemma);
		$indices1 = $collection->getAllWordIndices();
		$indices2 = $collection->getAllWordIndices();

		static::assertSame([], $indices1);
		static::assertSame([], $indices2);
	}

	public function testGetNounWordIndex(): void
	{
		$lemma = 'lemmmmmmma';
		$expectedIndex = $this->createMock(WordIndexInterface::class);

		$collection = $this->createCollection(PartOfSpeechEnum::NOUN(), $lemma, $expectedIndex);
		$actualIndex1 = $collection->getNounWordIndex();
		$actualIndex2 = $collection->getNounWordIndex();

		static::assertSame($expectedIndex, $actualIndex1);
		static::assertSame($expectedIndex, $actualIndex2);
	}

	public function testGetVerbWordIndex(): void
	{
		$lemma = 'lemmmmmmma';
		$expectedIndex = $this->createMock(WordIndexInterface::class);

		$collection = $this->createCollection(PartOfSpeechEnum::VERB(), $lemma, $expectedIndex);
		$actualIndex1 = $collection->getVerbWordIndex();
		$actualIndex2 = $collection->getVerbWordIndex();

		static::assertSame($expectedIndex, $actualIndex1);
		static::assertSame($expectedIndex, $actualIndex2);
	}

	public function testGetAdverbWordIndex(): void
	{
		$lemma = 'lemmmmmmma';
		$expectedIndex = $this->createMock(WordIndexInterface::class);

		$collection = $this->createCollection(PartOfSpeechEnum::ADVERB(), $lemma, $expectedIndex);
		$actualIndex1 = $collection->getAdverbWordIndex();
		$actualIndex2 = $collection->getAdverbWordIndex();

		static::assertSame($expectedIndex, $actualIndex1);
		static::assertSame($expectedIndex, $actualIndex2);
	}

	public function testGetAdjectiveWordIndex(): void
	{
		$lemma = 'lemmmmmmma';
		$expectedIndex = $this->createMock(WordIndexInterface::class);

		$collection = $this->createCollection(PartOfSpeechEnum::ADJECTIVE(), $lemma, $expectedIndex);
		$actualIndex1 = $collection->getAdjectiveWordIndex();
		$actualIndex2 = $collection->getAdjectiveWordIndex();

		static::assertSame($expectedIndex, $actualIndex1);
		static::assertSame($expectedIndex, $actualIndex2);
	}


	private function createCollection(PartOfSpeechEnum $partOfSpeech, string $lemma, WordIndexInterface $wordIndex): WordIndexCollection
	{
		$repository = $this->createMock(WordIndexMultiRepositoryInterface::class);
		$repository->expects($this->once())
			->method('findWordIndexByPartOfSpeech')
			->with($partOfSpeech, $lemma)
			->willReturn($wordIndex);

		return new WordIndexCollection($repository, $lemma);
	}
}
