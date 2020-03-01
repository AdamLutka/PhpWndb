<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests;

use AL\PhpWndb\Model\Indexes\WordIndexInterface;
use AL\PhpWndb\Model\Synsets\Collections\SynsetCollectionFactoryInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\WordIndexMultiRepositoryInterface;
use AL\PhpWndb\WordNet;

class WordNetTest extends BaseTestAbstract
{
	public function testSearchSynsetsFound(): void
	{
		$wordIndexNoun = $this->createMock(WordIndexInterface::class);
		$wordIndexNoun->method('getSynsetOffsets')->willReturn([1, 2]);
		$wordIndexNoun->method('getPartOfSpeech')->willReturn(PartOfSpeechEnum::NOUN());

		$wordIndexVerb = $this->createMock(WordIndexInterface::class);
		$wordIndexVerb->method('getSynsetOffsets')->willReturn([10, 20]);
		$wordIndexVerb->method('getPartOfSpeech')->willReturn(PartOfSpeechEnum::VERB());

		$wordIndexAdverb = $this->createMock(WordIndexInterface::class);
		$wordIndexAdverb->method('getSynsetOffsets')->willReturn([100, 200]);
		$wordIndexAdverb->method('getPartOfSpeech')->willReturn(PartOfSpeechEnum::ADVERB());

		$wordIndexAdjective = $this->createMock(WordIndexInterface::class);
		$wordIndexAdjective->method('getSynsetOffsets')->willReturn([1000, 2000]);
		$wordIndexAdjective->method('getPartOfSpeech')->willReturn(PartOfSpeechEnum::ADJECTIVE());

		$wordIndexRepository = $this->createMock(WordIndexMultiRepositoryInterface::class);
		$wordIndexRepository->method('findAllWordIndices')->willReturn([
			$wordIndexNoun,
			$wordIndexVerb,
			$wordIndexAdverb,
			$wordIndexAdjective
		]);

		$synsetCollectionFactory = $this->createMock(SynsetCollectionFactoryInterface::class);
		$synsetCollectionFactory->expects($this->once())->method('createSynsetCollection')->with(
			static::equalTo([1000, 2000]),
			static::equalTo([100, 200]),
			static::equalTo([1, 2]),
			static::equalTo([10, 20])
		);


		$wordNet = new WordNet($synsetCollectionFactory, $wordIndexRepository);
		$synsets = $wordNet->searchSynsets('lemma');
	}

	public function testSearchSynsetsNotFound(): void
	{
		$wordIndexRepository = $this->createMock(WordIndexMultiRepositoryInterface::class);
		$wordIndexRepository->method('findAllWordIndices')->willReturn([]);

		$synsetCollectionFactory = $this->createMock(SynsetCollectionFactoryInterface::class);
		$synsetCollectionFactory->expects($this->once())->method('createSynsetCollection')->with(
			static::equalTo([]),
			static::equalTo([]),
			static::equalTo([]),
			static::equalTo([])
		);


		$wordNet = new WordNet($synsetCollectionFactory, $wordIndexRepository);
		$synsets = $wordNet->searchSynsets('lemma');
	}
}
