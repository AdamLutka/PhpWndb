<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests;

use AL\PhpWndb\DiContainerFactory;
use AL\PhpWndb\WordNet;
use AL\PhpWndb\Model\Synsets\SynsetInterface;

class IntegrationTest extends BaseTestAbstract
{
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

		$expectedVerbOffsets = [1779085, 1832678, 1779456, 1429048];
		$expectedNounOffsets = [848145, 7558676, 5821331, 9869006, 7503480, 13617812];

		sort($verbOffsets);
		sort($expectedVerbOffsets);
		sort($nounOffsets);
		sort($expectedNounOffsets);

		static::assertSame($expectedVerbOffsets, $verbOffsets);
		static::assertSame($expectedNounOffsets, $nounOffsets);
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
}