<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Model\Indexes;

use AL\PhpWndb\DataMapping\LemmaMapper;
use AL\PhpWndb\DataMapping\PartOfSpeechMapper;
use AL\PhpWndb\DataMapping\RelationPointerTypeMapper;
use AL\PhpWndb\Model\Indexes\WordIndexFactory;
use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Tests\Fixtures\Parsing\ParsedIndexFixtures;
use AL\PhpWndb\Tests\BaseTestAbstract;

class WordIndexFactoryTest extends BaseTestAbstract
{
	/** @var ParsedIndexFixtures */
	protected $fixtures;


	public function setUp()
	{
		parent::setUp();
		$this->fixtures = new ParsedIndexFixtures($this);
	}


	public function testCreateWordIndexFromParsedData(): void
	{
		$parsedData = $this->fixtures->createWordIndex('abstraction', 'n', ['@', '~'], [123, 345, 567]);
		$wordIndex = $this->createFactory()->createWordIndexFromParsedData($parsedData);

		static::assertSame('abstraction', $wordIndex->getLemma());
		static::assertEnum(PartOfSpeechEnum::NOUN(), $wordIndex->getPartOfSpeech());
		static::assertSame([RelationPointerTypeEnum::HYPERNYM(), RelationPointerTypeEnum::HYPONYM()], $wordIndex->getRelationPointerTypes());
		static::assertSame([123, 345, 567], $wordIndex->getSynsetOffsets());
	}

	public function testCreateWordIndexFromParsedDataEmpty(): void
	{
		$parsedData = $this->fixtures->createWordIndex('do', 'v', [], []);
		$wordIndex = $this->createFactory()->createWordIndexFromParsedData($parsedData);

		static::assertSame('do', $wordIndex->getLemma());
		static::assertEnum(PartOfSpeechEnum::VERB(), $wordIndex->getPartOfSpeech());
		static::assertSame([], $wordIndex->getRelationPointerTypes());
		static::assertSame([], $wordIndex->getSynsetOffsets());
	}


	protected function createFactory(): WordIndexFactory
	{
		return new WordIndexFactory(
			new LemmaMapper(),
			new PartOfSpeechMapper(),
			new RelationPointerTypeMapper()
		);
	}
}
