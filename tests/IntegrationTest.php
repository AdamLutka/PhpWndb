<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests;

use AL\PhpWndb\DiContainerFactory;
use AL\PhpWndb\WordNet;
use AL\PhpWndb\Model\Synsets\SynsetInterface;

class IntegrationTest extends BaseTestAbstract
{
	private const
		LOVE_VERB_OFFSETS = [1779085, 1832678, 1779456, 1429048],
		LOVE_NOUN_OFFSETS = [848145, 7558676, 5821331, 9869006, 7503480, 13617812];


	/** @var WordNet */
	private $wordNet;


	public function setUp(): void
	{
		$containerFactory = new DiContainerFactory();
		$container = $containerFactory->createContainer();

		$this->wordNet = $container->get(WordNet::class);
	}


	public function testSearchSynsets(): void
	{
		$synsetCollection = $this->wordNet->searchSynsets('love');

		$verbs = $synsetCollection->getSynsetVerbs();
		$nouns = $synsetCollection->getSynsetNouns();

		$verbOffsets = $this->synsetsToOffsets($verbs);
		$nounOffsets = $this->synsetsToOffsets($nouns);

		static::assertOffsets(self::LOVE_VERB_OFFSETS, $verbOffsets);
		static::assertOffsets(self::LOVE_NOUN_OFFSETS, $nounOffsets);
	}

	public function testSearchWordIndices(): void
	{
		$wordIndicesConnection = $this->wordNet->searchWordIndices('love');

		static::assertNull($wordIndicesConnection->getAdverbWordIndex());
		static::assertNull($wordIndicesConnection->getAdjectiveWordIndex());

		$verbIndex = $wordIndicesConnection->getVerbWordIndex();
		$nounIndex = $wordIndicesConnection->getNounWordIndex();
		$allIndices = $wordIndicesConnection->getAllWordIndices();

		static::assertNotNull($verbIndex);
		static::assertNotNull($nounIndex);

		static::assertOffsets(self::LOVE_VERB_OFFSETS, $verbIndex->getSynsetOffsets());
		static::assertOffsets(self::LOVE_NOUN_OFFSETS, $nounIndex->getSynsetOffsets());

		static::assertContains($verbIndex, $allIndices);
		static::assertContains($nounIndex, $allIndices);
		static::assertCount(2, $allIndices);
	}


	/**
	 * @param array<mixed> $synsets
	 * @return int[]
	 */
	private function synsetsToOffsets(array $synsets): array
	{
		return array_map(function ($synset) {
			static::assertInstanceOf(SynsetInterface::class, $synset);
			return $synset->getSynsetOffset();
		}, $synsets);
	}


	/**
	 * @param int[] $expectedOffsets
	 * @param int[] $actualOffsets
	 */
	private static function assertOffsets(array $expectedOffsets, array $actualOffsets): void
	{
		sort($expectedOffsets);
		sort($actualOffsets);

		static::assertSame($expectedOffsets, $actualOffsets);
	}
}