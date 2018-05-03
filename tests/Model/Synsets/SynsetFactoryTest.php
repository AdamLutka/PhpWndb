<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Model\Synsets;

use AL\PhpEnum\Enum;
use AL\PhpWndb\DataMapping\LemmaMapper;
use AL\PhpWndb\DataMapping\PartOfSpeechMapper;
use AL\PhpWndb\DataMapping\RelationPointerTypeMapper;
use AL\PhpWndb\DataMapping\SynsetCategoryMapper;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Model\Relations\RelationPointerFactory;
use AL\PhpWndb\Model\Relations\RelationPointerInterface;
use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\Model\Relations\RelationsFactory;
use AL\PhpWndb\Model\Synsets\Nouns\SynsetNounsCategoryEnum;
use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\Model\Synsets\SynsetFactory;
use AL\PhpWndb\Model\Synsets\Verbs\SynsetVerbsCategoryEnum;
use AL\PhpWndb\Model\Words\WordFactory;
use AL\PhpWndb\Model\Words\WordInterface;
use AL\PhpWndb\Tests\BaseTestAbstract;
use AL\PhpWndb\Tests\Fixtures\Parsing\ParsedDataFixtures;

class SynsetFactoryTest extends BaseTestAbstract
{
	/** @var ParsedDataFixtures */
	protected $fixtures;


	public function setUp()
	{
		parent::setUp();
		$this->fixtures = new ParsedDataFixtures($this);
	}


	public function testCreateSynsetFromParsedData(): void
	{
		$wordsData = [
			$this->fixtures->createWordData('word_1', 1),
			$this->fixtures->createWordData('word_2', 2),
			$this->fixtures->createWordData('word_3', 3),
		];
		$pointersData = [
			$this->fixtures->createPointerData('@', 11, 'v', 1, 2),
			$this->fixtures->createPointerData('!', 22, 'a', 0, 0),
			$this->fixtures->createPointerData('~', 33, 'v', 1, 0),
		];
		$framesData = [
			$this->fixtures->createFrameData(33, 0),
			$this->fixtures->createFrameData(1, 2),
			$this->fixtures->createFrameData(2, 2),
		];
		$synsetData = $this->fixtures->createSynsetData(135, 43, 'v', 'gloss', $wordsData, $pointersData, $framesData);

		$factory = $this->createFactory();
		$synset = $factory->createSynsetFromParseData($synsetData);

		static::assertSynset(135, 'gloss', SynsetVerbsCategoryEnum::WEATHER(), 3, $synset);

		$words = $synset->getWords();
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
		$synsetData = $this->fixtures->createSynsetData(1248, 22, 'n', 'nouns', [], [], []);

		$factory = $this->createFactory();
		$synset = $factory->createSynsetFromParseData($synsetData);

		static::assertSynset(1248, 'nouns', SynsetNounsCategoryEnum::PROCESS(), 0, $synset);
	}

	/**
	 * @expectedException \AL\PhpWndb\Model\Exceptions\SynsetCreateException
	 * @expectedExceptionMessage There should not be any frames
	 */
	public function testAdverbWithFrames(): void
	{
		$framesData = [
			$this->fixtures->createFrameData(23, 0),
		];
		$synsetData = $this->fixtures->createSynsetData(135, 2, 'r', 'gloss', [], [], $framesData);

		$factory = $this->createFactory();
		$factory->createSynsetFromParseData($synsetData);
	}

	/**
	 * @expectedException \AL\PhpWndb\Model\Exceptions\SynsetCreateException
	 * @expectedExceptionMessage Index (0) has to be less than 0.
	 */
	public function testInvalidPointer(): void
	{
		$pointersData = [
			$this->fixtures->createPointerData('@', 123, 'v', 1, 0),
		];
		$synsetData = $this->fixtures->createSynsetData(135, 2, 'r', 'gloss', [], $pointersData, []);

		$factory = $this->createFactory();
		$factory->createSynsetFromParseData($synsetData);
	}


	protected function createFactory(): SynsetFactory
	{
		return new SynsetFactory(
			new SynsetCategoryMapper(),
			new PartOfSpeechMapper(),
			new RelationPointerTypeMapper(),
			new LemmaMapper(),
			new RelationsFactory(),
			new RelationPointerFactory(),
			new WordFactory()
		);
	}


	protected static function assertSynset(
		int $expectedSynsetOffset,
		string $expectedGloss,
		Enum $expectedSynsetCategory,
		int $expectedWordsCount,
		$synset
	): void {
		static::assertInstanceOf(SynsetInterface::class, $synset);
		static::assertSame($expectedSynsetOffset, $synset->getSynsetOffset());
		static::assertSame($expectedGloss, $synset->getGloss());
		static::assertEnum($expectedSynsetCategory, $synset->getSynsetCategory());
		static::assertCount($expectedWordsCount, $synset->getWords());
	}

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
