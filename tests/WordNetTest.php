<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests;

use AL\PhpWndb\Model\Indexes\WordIndexInterface;
use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\SynsetMultiRepositoryInterface;
use AL\PhpWndb\Repositories\WordIndexMultiRepositoryInterface;
use AL\PhpWndb\WordNet;

class WordNetTest extends BaseTestAbstract
{
	public function testSearchLemmaFound(): void
	{
		$wordNet = $this->createWordNet([111, 122, 123]);
		$synsets = $wordNet->searchLemma('cat');

		static::assertCount(3, $synsets);
		static::assertSame(111, $synsets[0]->getSynsetOffset());
		static::assertSame(122, $synsets[1]->getSynsetOffset());
		static::assertSame(123, $synsets[2]->getSynsetOffset());
	}

	public function testSearchLemmaNotFound(): void
	{
		$wordNet = $this->createWordNet(null);
		static::assertSame([], $wordNet->searchLemma('cat'));
	}


	/**
	 * @param int[]|null $synsetOffsets
	 */
	private function createWordNet(?array $synsetOffsets): WordNet
	{
		$synsetRepository = $this->createMock(SynsetMultiRepositoryInterface::class);
		$wordIndexRepository = $this->createMock(WordIndexMultiRepositoryInterface::class);

		if ($synsetOffsets !== null) {
			$wordIndex = $this->createMock(WordIndexInterface::class);
			$wordIndex->method('getSynsetOffsets')->willReturn($synsetOffsets);
			$wordIndex->method('getPartOfSpeech')->willReturn(PartOfSpeechEnum::NOUN());

			$valueMap = array_map(function (int $synsetOffset): array {
				$synset = $this->createMock(SynsetInterface::class);
				$synset->method('getSynsetOffset')->willReturn($synsetOffset);

				return [PartOfSpeechEnum::NOUN(), $synsetOffset, $synset];
			}, $synsetOffsets);

			$synsetRepository->method('getSynsetByPartOfSpeech')->will($this->returnValueMap($valueMap));
		}
		else {
			$wordIndex = null;
		}

		$wordIndexRepository->method('findWordIndex')->willReturn($wordIndex);

		return new WordNet($synsetRepository, $wordIndexRepository);
	}
}
