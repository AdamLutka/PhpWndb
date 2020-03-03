<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Model\Synsets;

use AL\PhpEnum\Enum;
use AL\PhpWndb\Cache\CacheInterface;
use AL\PhpWndb\DataMapping\LemmaMapper;
use AL\PhpWndb\DataMapping\PartOfSpeechMapper;
use AL\PhpWndb\DataMapping\RelationPointerTypeMapper;
use AL\PhpWndb\DataMapping\SynsetCategoryMapper;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Model\Exceptions\SynsetCreateException;
use AL\PhpWndb\Model\Relations\RelationPointerFactory;
use AL\PhpWndb\Model\Relations\RelationPointerInterface;
use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\Model\Relations\RelationsFactory;
use AL\PhpWndb\Model\Synsets\Nouns\SynsetNounsCategoryEnum;
use AL\PhpWndb\Model\Synsets\Nouns\SynsetNounsInterface;
use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\Model\Synsets\SynsetFactory;
use AL\PhpWndb\Model\Synsets\Verbs\SynsetVerbsCategoryEnum;
use AL\PhpWndb\Model\Synsets\Verbs\SynsetVerbsInterface;
use AL\PhpWndb\Model\Words\VerbInterface;
use AL\PhpWndb\Model\Words\WordFactory;
use AL\PhpWndb\Model\Words\WordInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedFrameDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedPointerDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedSynsetDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedWordDataInterface;
use AL\PhpWndb\Tests\BaseTestAbstract;

class SynsetFactoryTest extends BaseTestAbstract
{
	public function testCreateSynsetFromParsedData(): void
	{
		$wordsData = [
			$this->createWordData('word_1', 1),
			$this->createWordData('word_2', 2),
			$this->createWordData('word_3', 3),
		];
		$pointersData = [
			$this->createPointerData('@', 11, 'v', 1, 2),
			$this->createPointerData('!', 22, 'a', 0, 0),
			$this->createPointerData('~', 33, 'v', 1, 0),
		];
		$framesData = [
			$this->createFrameData(33, 0),
			$this->createFrameData(1, 2),
			$this->createFrameData(2, 2),
		];
		$synsetData = $this->createSynsetData(135, 43, 'v', 'gloss', $wordsData, $pointersData, $framesData);

		$factory = $this->createFactory();
		$synset = $factory->createSynsetFromParsedData($synsetData);

		static::assertInstanceOf(SynsetVerbsInterface::class, $synset);
		static::assertSame(135, $synset->getSynsetOffset());
		static::assertSame('gloss', $synset->getGloss());
		static::assertEnum(SynsetVerbsCategoryEnum::WEATHER(), $synset->getSynsetCategory());
		static::assertCount(3, $synset->getWords());

		$words = $synset->getWords();

		static::assertInstanceOf(VerbInterface::class, $words[0]);
		static::assertInstanceOf(VerbInterface::class, $words[1]);
		static::assertInstanceOf(VerbInterface::class, $words[2]);

		static::assertWord('word 1', 1, 3, $words[0]);
		static::assertWord('word 2', 2, 1, $words[1]);
		static::assertWord('word 3', 3, 1, $words[2]);
		
		static::assertSame([33], $words[0]->getFrames());
		static::assertSame([33, 1, 2], $words[1]->getFrames());
		static::assertSame([33], $words[2]->getFrames());

		$pointers0 = $words[0]->getAllRelated();
		static::assertRelationPointer(RelationPointerTypeEnum::HYPERNYM(), PartOfSpeechEnum::VERB(), 11, 2, $pointers0[0]);
		static::assertRelationPointer(RelationPointerTypeEnum::ANTONYM(), PartOfSpeechEnum::ADJECTIVE(), 22, null, $pointers0[1]);
		static::assertRelationPointer(RelationPointerTypeEnum::HYPONYM(), PartOfSpeechEnum::VERB(), 33, null, $pointers0[2]);

		$pointers1 = $words[1]->getAllRelated();
		static::assertRelationPointer(RelationPointerTypeEnum::ANTONYM(), PartOfSpeechEnum::ADJECTIVE(), 22, null, $pointers1[0]);

		$pointers2 = $words[2]->getAllRelated();
		static::assertRelationPointer(RelationPointerTypeEnum::ANTONYM(), PartOfSpeechEnum::ADJECTIVE(), 22, null, $pointers2[0]);
	}

	public function testEmptySynset(): void
	{
		$synsetData = $this->createSynsetData(1248, 22, 'n', 'nouns', [], [], []);

		$factory = $this->createFactory();
		$synset = $factory->createSynsetFromParsedData($synsetData);

		static::assertInstanceOf(SynsetNounsInterface::class, $synset);
		static::assertSame(1248, $synset->getSynsetOffset());
		static::assertSame('nouns', $synset->getGloss());
		static::assertEnum(SynsetNounsCategoryEnum::PROCESS(), $synset->getSynsetCategory());
		static::assertCount(0, $synset->getWords());

	}

	public function testAdverbWithFrames(): void
	{
		$this->expectException(SynsetCreateException::class);
		$this->expectExceptionMessage('There should not be any frames');

		$framesData = [
			$this->createFrameData(23, 0),
		];
		$synsetData = $this->createSynsetData(135, 2, 'r', 'gloss', [], [], $framesData);

		$factory = $this->createFactory();
		$factory->createSynsetFromParsedData($synsetData);
	}

	public function testInvalidPointer(): void
	{
		$this->expectException(SynsetCreateException::class);
		$this->expectExceptionMessage('Index (0) has to be less than 0.');

		$pointersData = [
			$this->createPointerData('@', 123, 'v', 1, 0),
		];
		$synsetData = $this->createSynsetData(135, 2, 'r', 'gloss', [], $pointersData, []);

		$factory = $this->createFactory();
		$factory->createSynsetFromParsedData($synsetData);
	}


	protected function createFactory(): SynsetFactory
	{
		return new SynsetFactory(
			new SynsetCategoryMapper(),
			new PartOfSpeechMapper(),
			new RelationPointerTypeMapper(),
			new LemmaMapper($this->createMock(CacheInterface::class)),
			new RelationsFactory(),
			new RelationPointerFactory(),
			new WordFactory()
		);
	}

	protected function createWordData(string $value, int $lexId): ParsedWordDataInterface
	{
		$mock = $this->createMock(ParsedWordDataInterface::class);
		$mock->method('getValue')->willReturn($value);
		$mock->method('getLexId')->willReturn($lexId);

		return $mock;
	}

	protected function createPointerData(
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

	protected function createFrameData(int $frameNumber, int $wordIndex): ParsedFrameDataInterface
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
	protected function createSynsetData(
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


	/**
	 * @param mixed $word
	 */
	protected static function assertWord(
		string $expectedLemma,
		int $expectedLexId,
		int $expectedRelatedCount,
		$word
	): void {
		static::assertInstanceOf(WordInterface::class, $word);
		static::assertSame($expectedLemma, $word->getLemma());
		static::assertSame($expectedLexId, $word->getLexId());
		static::assertCount($expectedRelatedCount, $word->getAllRelated());
	}

	/**
	 * @param mixed $pointer
	 */
	protected static function assertRelationPointer(
		RelationPointerTypeEnum $expectedPointerType,
		PartOfSpeechEnum $expectedPartOfSpeech,
		int $expectedTargetSynsetOffset,
		?int $expectedTargetWordIndex,
		$pointer
	): void {
		static::assertInstanceOf(RelationPointerInterface::class, $pointer);
		static::assertEnum($expectedPointerType, $pointer->getPointerType());
		static::assertEnum($expectedPartOfSpeech, $pointer->getTargetPartOfSpeech());
		static::assertSame($expectedTargetSynsetOffset, $pointer->getTargetSynsetOffset());
		static::assertSame($expectedTargetWordIndex, $pointer->getTargetWordIndex());
	}
}
