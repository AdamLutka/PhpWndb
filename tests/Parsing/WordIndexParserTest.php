<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Parsing;

use AL\PhpWndb\Parsing\Exceptions\WordIndexParseException;
use AL\PhpWndb\Parsing\WordIndexParser;
use AL\PhpWndb\Tests\BaseTestAbstract;

class WordIndexParserTest extends BaseTestAbstract
{
	public function testParseWordIndex(): void
	{
		$wordIndex = 'accelerator n 4 6 @ ~ #p %p + ; 4 2 02673313 02672816 14747789 02673012  ';
		$parser = $this->createParser();
		$parseResult = $parser->parseWordIndex($wordIndex);

		static::assertSame('accelerator', $parseResult->getLemma());
		static::assertSame('n', $parseResult->getPartOfSpeech());
		static::assertSame(['@', '~', '#p', '%p', '+', ';'], $parseResult->getPointerTypes());
		static::assertSame([2673313, 2672816, 14747789, 2673012], $parseResult->getSynsetOffsets());
	}

	
	/**
	 * @dataProvider dpTestParseWordIndexInvalid
	 */
	public function testParseWordIndexInvalid(string $wordIndex): void
	{
		$this->expectException(WordIndexParseException::class);

		$parser = $this->createParser();
		$parser->parseWordIndex($wordIndex);
	}

	/**
	 * @return array<string, array<string>>
	 */
	public function dpTestParseWordIndexInvalid(): array
	{
		return [
			'too few tokens' =>
				['acceleration'],
			'too many tokens' =>
				['accelerator n 4 6 @ ~ #p %p + ; 4 2 02673313 02672816 14747789 02673012  more tokens'],
			'invalid pointers count' =>
				['accelerator n 4 NaN @ ~ #p %p + ; 4 2 02673313 02672816 14747789 02673012  '],
			'invalid synset offsets count' =>
				['accelerator n 4 6 @ ~ #p %p + ; NaN 2 02673313 02672816 14747789 02673012  '],
		];
	}


	private function createParser(): WordIndexParser
	{
		return new WordIndexParser();
	}
}
