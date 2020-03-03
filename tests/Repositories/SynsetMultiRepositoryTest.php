<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Repositories;

use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;
use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\SynsetMultiRepository;
use AL\PhpWndb\Repositories\SynsetRepositoryInterface;
use AL\PhpWndb\Tests\BaseTestAbstract;
use UnexpectedValueException;

class SynsetMultiRepositoryTest extends BaseTestAbstract
{
	public function testFindSynset(): void
	{
		$repository = $this->createMultiRepository();
		$synset = $repository->findSynset(100);

		static::assertNotNull($synset);
	}

	public function testFindSynsetUnknown(): void
	{
		$repository = $this->createMultiRepository();
		$synset = $repository->findSynset(-100);

		static::assertNull($synset);
	}


	public function testGetSynset(): void
	{
		$repository = $this->createMultiRepository();
		$synset = $repository->getSynset(100);

		static::assertNotNull($synset);
	}

	public function testGetSynsetUnknown(): void
	{
		$this->expectException(UnknownSynsetOffsetException::class);

		$repository = $this->createMultiRepository();
		$repository->getSynset(-100);
	}


	/**
	 * @dataProvider dpPartOfSpeeches
	 */
	public function testFindSynsetByPartOfSpeech(PartOfSpeechEnum $partOfSpeech): void
	{
		$repository = $this->createMultiRepository();
		$synset = $repository->findSynsetByPartOfSpeech($partOfSpeech, 1000);

		static::assertNotNull($synset);
		static::assertEnum($partOfSpeech, $synset->getPartOfSpeech());
	}

	public function testFindSynsetByPartOfSpeechUnknownPartOfSpeech(): void
	{
		$this->expectException(UnexpectedValueException::class);
		$this->expectExceptionMessage('Repository for ADVERB is not registered.');

		$repository = new SynsetMultiRepository();
		$repository->findSynsetByPartOfSpeech(PartOfSpeechEnum::ADVERB(), 100);
	}

	public function testFindSynsetByPartOfSpeechUnknownSynset(): void
	{
		$repository = $this->createMultiRepository();
		$synset = $repository->findSynsetByPartOfSpeech(PartOfSpeechEnum::VERB(), -100);

		static::assertNull($synset);
	}


	/**
	 * @dataProvider dpPartOfSpeeches
	 */
	public function testGetSynsetByPartOfSpeech(PartOfSpeechEnum $partOfSpeech): void
	{
		$repository = $this->createMultiRepository();
		$synset = $repository->getSynsetByPartOfSpeech($partOfSpeech, 1000);

		static::assertEnum($partOfSpeech, $synset->getPartOfSpeech());
	}

	public function testGetSynsetByPartOfSpeechUnknownPartOfSpeech(): void
	{
		$this->expectException(UnexpectedValueException::class);
		$this->expectExceptionMessage('Repository for ADVERB is not registered.');

		$repository = new SynsetMultiRepository();
		$repository->getSynsetByPartOfSpeech(PartOfSpeechEnum::ADVERB(), 100);
	}

	public function testGetSynsetByPartOfSpeechUnknownSynset(): void
	{
		$this->expectException(UnknownSynsetOffsetException::class);

		$repository = $this->createMultiRepository();
		$repository->getSynsetByPartOfSpeech(PartOfSpeechEnum::VERB(), -100);
	}


	/**
	 * @return array<array<PartOfSpeechEnum>>
	 */
	public function dpPartOfSpeeches(): array
	{
		return [
			[PartOfSpeechEnum::ADJECTIVE()],
			[PartOfSpeechEnum::NOUN()],
			[PartOfSpeechEnum::VERB()],
		];
	}


	protected function createMultiRepository(): SynsetMultiRepository
	{
		$repository = new SynsetMultiRepository();
		$repository->addRepository(PartOfSpeechEnum::ADJECTIVE(), $this->createRepository(PartOfSpeechEnum::ADJECTIVE()));
		$repository->addRepository(PartOfSpeechEnum::NOUN(), $this->createRepository(PartOfSpeechEnum::NOUN()));
		$repository->addRepository(PartOfSpeechEnum::VERB(), $this->createRepository(PartOfSpeechEnum::VERB()));

		return $repository;
	}

	protected function createRepository(PartOfSpeechEnum $partOfSpeech): SynsetRepositoryInterface
	{
		$synset = $this->createMock(SynsetInterface::class);
		$synset->method('getPartOfSpeech')->willReturn($partOfSpeech);

		$repository = $this->createMock(SynsetRepositoryInterface::class);
		$repository->method('getSynset')->willReturnCallback(function ($synsetOffset) use ($synset) {
			if ($synsetOffset > 0) {
				return $synset;
			}

			throw new UnknownSynsetOffsetException($synsetOffset);
		});
		$repository->method('findSynset')->willReturnCallback(function ($synsetOffset) use ($synset) {
			return $synsetOffset > 0 ? $synset : null;
		});

		return $repository;
	}
}
