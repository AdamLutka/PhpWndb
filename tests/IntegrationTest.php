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


	public function testSearchLemma(): void
	{
		$synsets = $this->wordNet->searchLemma('love');
		
		$actualSynsetOffsets = array_map(function ($synset) {
			static::assertInstanceOf(SynsetInterface::class, $synset);
			return $synset->getSynsetOffset();
		}, $synsets);
		$expectedSynsetOffsets = [7558676, 5821331, 9869006, 7503480, 13617812, 848145, 1779085, 1832678, 1779456, 1429048];

		sort($actualSynsetOffsets);
		sort($expectedSynsetOffsets);

		static::assertSame($expectedSynsetOffsets, $actualSynsetOffsets);
	}
}