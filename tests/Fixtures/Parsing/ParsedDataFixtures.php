<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Fixtures\Parsing;

use AL\PhpWndb\Parsing\ParsedData\ParsedFrameDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedPointerDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedSynsetDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedWordDataInterface;
use AL\PhpWndb\Tests\Fixtures\AbstractFixtures;

class ParsedDataFixtures extends AbstractFixtures
{
	public function createWordData(string $value, int $lexId): ParsedWordDataInterface
	{
		$mock = $this->createMock(ParsedWordDataInterface::class);
		$mock->method('getValue')->willReturn($value);
		$mock->method('getLexId')->willReturn($lexId);

		return $mock;
	}

	public function createPointerData(
		string $pointerType,
		int $synsetOffset,
		string $partOfSpeech,
		int $sourceWordIndex,
		int $targetWordIndex
	): ParsedPointerDataInterface {
		$mock = $this->createMock(ParsedPointerDataInterface::class);
		$mock->method('getPointerType')->willReturn($pointerType);
		$mock->method('getSynsetOffset')->willReturn($synsetOffset);
		$mock->method('getPartOfSpeech')->willReturn($partOfSpeech);
		$mock->method('getSourceWordIndex')->willReturn($sourceWordIndex);
		$mock->method('getTargetWordIndex')->willReturn($targetWordIndex);

		return $mock;
	}

	public function createFrameData(int $frameNumber, int $wordIndex): ParsedFrameDataInterface
	{
		$mock = $this->createMock(ParsedFrameDataInterface::class);
		$mock->method('getFrameNumber')->willReturn($frameNumber);
		$mock->method('getWordIndex')->willReturn($wordIndex);

		return $mock;
	}

	/**
	 * @param ParsedWordDataInterface[] $words
	 * @param ParsedPointerDataInterface[] $pointers
	 * @param ParsedFrameDataInterface[] $frames
	 */
	public function createSynsetData(
		int $synsetOffset,
		int $lexFileNumber,
		string $partOfSpeech,
		string $gloss,
		array $words,
		array $pointers,
		array $frames
	): ParsedSynsetDataInterface {
		$mock = $this->createMock(ParsedSynsetDataInterface::class);
		$mock->method('getSynsetOffset')->willReturn($synsetOffset);
		$mock->method('getLexFileNumber')->willReturn($lexFileNumber);
		$mock->method('getPartOfSpeech')->willReturn($partOfSpeech);
		$mock->method('getGloss')->willReturn($gloss);
		$mock->method('getWords')->willReturn($words);
		$mock->method('getPointers')->willReturn($pointers);
		$mock->method('getFrames')->willReturn($frames);

		return $mock;
	}
}
