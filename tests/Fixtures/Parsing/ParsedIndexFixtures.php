<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Fixtures\Parsing;

use AL\PhpWndb\Parsing\ParsedData\ParsedWordIndexInterface;
use AL\PhpWndb\Tests\Fixtures\AbstractFixtures;

class ParsedIndexFixtures extends AbstractFixtures
{
	/**
	 * @param string[] $relationPointerTypes
	 * @param int[] $synsetOffsets
	 */
	public function createWordIndex(
		string $lemma,
		string $partOfSpeech,
		iterable $relationPointerTypes,
		iterable $synsetOffsets
	): ParsedWordIndexInterface {
		$wordIndex = $this->createMock(ParsedWordIndexInterface::class);
		$wordIndex->method('getLemma')->willReturn($lemma);
		$wordIndex->method('getPartOfSpeech')->willReturn($partOfSpeech);
		$wordIndex->method('getPointerTypes')->willReturn($relationPointerTypes);
		$wordIndex->method('getSynsetOffsets')->willReturn($synsetOffsets);

		return $wordIndex;
	}
}
