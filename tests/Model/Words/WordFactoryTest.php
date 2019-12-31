<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Model\Words;

use AL\PhpWndb\Model\Relations\RelationsInterface;
use AL\PhpWndb\Tests\BaseTestAbstract;
use AL\PhpWndb\Model\Words\Adjective;
use AL\PhpWndb\Model\Words\Adverb;
use AL\PhpWndb\Model\Words\Noun;
use AL\PhpWndb\Model\Words\Verb;
use AL\PhpWndb\Model\Words\WordFactory;

class WordFactoryTest extends BaseTestAbstract
{
	public function testCreateAdjective(): void
	{
		$lexId = 111;
		$lemma = 'funny';
		$relations = $this->createMock(RelationsInterface::class);

		$factory = new WordFactory();
		$adjective = $factory->createAdjective($lemma, $lexId, $relations);

		static::assertInstanceOf(Adjective::class, $adjective);
		static::assertSame($lemma, $adjective->getLemma());
		static::assertSame($lexId, $adjective->getLexId());
	}

	public function testCreateAdverb(): void
	{
		$lexId = 222;
		$lemma = 'colorfully';
		$relations = $this->createMock(RelationsInterface::class);

		$factory = new WordFactory();
		$adverb = $factory->createAdverb($lemma, $lexId, $relations);

		static::assertInstanceOf(Adverb::class, $adverb);
		static::assertSame($lemma, $adverb->getLemma());
		static::assertSame($lexId, $adverb->getLexId());
	}

	public function testCreateNoun(): void
	{
		$lexId = 333;
		$lemma = 'cat';
		$relations = $this->createMock(RelationsInterface::class);

		$factory = new WordFactory();
		$noun = $factory->createNoun($lemma, $lexId, $relations);

		static::assertInstanceOf(Noun::class, $noun);
		static::assertSame($lemma, $noun->getLemma());
		static::assertSame($lexId, $noun->getLexId());
	}

	public function testCreateVerb(): void
	{
		$lexId = 444;
		$lemma = 'run';
		$relations = $this->createMock(RelationsInterface::class);
		$frames = [1, 2, 3];

		$factory = new WordFactory();
		$verb = $factory->createVerb($lemma, $lexId, $relations, $frames);

		static::assertInstanceOf(Verb::class, $verb);
		static::assertSame($lemma, $verb->getLemma());
		static::assertSame($lexId, $verb->getLexId());
		static::assertSame($frames, $verb->getFrames());
	}
}
