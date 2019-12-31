<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Parsing;

use AL\PhpWndb\DataMapping\LemmaMapperInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedFrameDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedPointerDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedSynsetDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedWordDataInterface;
use AL\PhpWndb\Parsing\SynsetDataParser;
use AL\PhpWndb\Tests\BaseTestAbstract;

class SynsetDataParserTest extends BaseTestAbstract
{
	public function testParseSynsetDataNoun(): void
	{
		$synsetData = '00098064 04 n 01 invocation 1 002 @ 00044888 n 0000 + 00757492 v 0102 | the act of appealing for help';
		$parser = $this->createParser();
		$parseResult = $parser->parseSynsetData($synsetData);

		static::assertSynset(98064, 4, 'n', 'the act of appealing for help', 1, 2, 0, $parseResult);

		$words = $parseResult->getWords();
		static::assertWord('invocation', 1, $words[0]);

		$pointers = $parseResult->getPointers();
		static::assertPointer('@',  44888, 'n', 0, 0, $pointers[0]);
		static::assertPointer('+', 757492, 'v', 1, 2, $pointers[1]);
	}

	public function testParseSynsetDataVerb(): void
	{
		$synsetData = '00306627 30 v 02 implode 0 go_off 2 004 @ 01993067 v 0000 + 07380124 n 0101 + 07131012 n 0101 ! 00306798 v 0101 02 + 01 00 + 08 01 | burst inward; "The bottle imploded"  ';
		$parser = $this->createParser();
		$parseResult = $parser->parseSynsetData($synsetData);

		static::assertSynset(306627, 30, 'v', 'burst inward; "The bottle imploded"', 2, 4, 2, $parseResult);

		$words = $parseResult->getWords();
		static::assertWord('implode', 0, $words[0]);
		static::assertWord('go_off',  2, $words[1]);

		$pointers = $parseResult->getPointers();
		static::assertPointer('@', 1993067, 'v', 0, 0, $pointers[0]);
		static::assertPointer('+', 7380124, 'n', 1, 1, $pointers[1]);
		static::assertPointer('+', 7131012, 'n', 1, 1, $pointers[2]);
		static::assertPointer('!',  306798, 'v', 1, 1, $pointers[3]);

		$frames = $parseResult->getFrames();
		static::assertFrame(1, 0, $frames[0]);
		static::assertFrame(8, 1, $frames[1]);
	}


	/**
	 * @expectedException \AL\PhpWndb\Parsing\Exceptions\SynsetDataParseException
	 * @dataProvider dpTestParseSynsetDataInvalid
	 */
	public function testParseSynsetDataInvalid(string $synsetData): void
	{
		$parser = $this->createParser();
		$parser->parseSynsetData($synsetData);
	}

	/**
	 * @return array<string, array<string>>
	 */
	public function dpTestParseSynsetDataInvalid(): array
	{
		return [
			'too few tokens' =>
				['synset | gloss'],
			'too many tokens' =>
				['00098064 04 n 01 invocation 1 002 @ 00044888 n 0000 + 00757492 v 0102 some extra tokens | the act of appealing for help'],
			'invalid lexFileNumber' =>
				['00098064 NaN n 01 invocation 1 002 @ 00044888 n 0000 + 00757492 v 0102 | the act of appealing for help'],
			'invalid words count' =>
				['00098064 04 n NaN invocation 1 002 @ 00044888 n 0000 + 00757492 v 0102 | the act of appealing for help'],
			'invalid word lexId' =>
				['00098064 04 n 01 invocation NaN 002 @ 00044888 n 0000 + 00757492 v 0102 | the act of appealing for help'],
			'invalid pointers count' =>
				['00098064 04 n 01 invocation 1 NaN @ 00044888 n 0000 + 00757492 v 0102 | the act of appealing for help'],
			'invalid pointer synset offset' =>
				['00098064 04 n 01 invocation 1 002 @ NaN n 0000 + 00757492 v 0102 | the act of appealing for help'],
			'too short pointer source/target index' =>
				['00098064 04 n 01 invocation 1 002 @ 00044888 n 000 + 00757492 v 0102 | the act of appealing for help'],
			'invalid pointer source/target index' =>
				['00098064 04 n 01 invocation 1 002 @ 00044888 n 0z00 + 00757492 v 0102 | the act of appealing for help'],
			'missing gloss' =>
				['00098064 04 n 01 invocation 1 002 @ 00044888 n 0000 + 00757492 v 0102'],
		];
	}


	private function createParser(): SynsetDataParser
	{
		return new SynsetDataParser();
	}

	/**
	 * @param mixed $synset
	 */
	private static function assertSynset(
		int $expectedSynsetOffset,
		int $expectedLexFileNumber,
		string $expectedPartOfSpeech,
		string $expectedGloss,
		int $expectedWordsCount,
		int $expectedPointersCount,
		int $expectedFramesCount,
		$synset
	): void {
		static::assertInstanceOf(ParsedSynsetDataInterface::class, $synset);
		static::assertSame($expectedSynsetOffset, $synset->getSynsetOffset());
		static::assertSame($expectedLexFileNumber, $synset->getLexFileNumber());
		static::assertSame($expectedPartOfSpeech, $synset->getPartOfSpeech());
		static::assertSame($expectedGloss, $synset->getGloss());
		static::assertCount($expectedWordsCount, $synset->getWords());
		static::assertCount($expectedPointersCount, $synset->getPointers());
		static::assertCount($expectedFramesCount, $synset->getFrames());
	}

	/**
	 * @param mixed $word
	 */
	private static function assertWord(string $expectedValue, int $expectedLexId, $word): void
	{
		static::assertInstanceOf(ParsedWordDataInterface::class, $word);
		static::assertSame($expectedValue, $word->getValue());
		static::assertSame($expectedLexId, $word->getLexId());
	}

	/**
	 * @param mixed $pointer
	 */
	private static function assertPointer(
		string $expectedPointerType,
		int $expectedSynsetOffset,
		string $expectedPartOfSpeech,
		int $expectedSourceWordIndex,
		int $expectedTargetWordIndex,
		$pointer
	): void {
		static::assertInstanceOf(ParsedPointerDataInterface::class, $pointer);
		static::assertSame($expectedPointerType, $pointer->getPointerType());
		static::assertSame($expectedSynsetOffset, $pointer->getSynsetOffset());
		static::assertSame($expectedPartOfSpeech, $pointer->getPartOfSpeech());
		static::assertSame($expectedSourceWordIndex, $pointer->getSourceWordIndex());
		static::assertSame($expectedTargetWordIndex, $pointer->getTargetWordIndex());
	}

	/**
	 * @param mixed $frame
	 */
	private static function assertFrame(int $expectedFrameNumber, int $expectedWordIndex, $frame): void
	{
		static::assertInstanceOf(ParsedFrameDataInterface::class, $frame);
		static::assertSame($expectedFrameNumber, $frame->getFrameNumber());
		static::assertSame($expectedWordIndex, $frame->getWordIndex());
	}
}
