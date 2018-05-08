<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Repositories;

use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;
use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\SynsetMultiRepository;
use AL\PhpWndb\Repositories\SynsetRepositoryInterface;
use AL\PhpWndb\Tests\BaseTestAbstract;

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

	/**
	 * @expectedException \AL\PhpWndb\Exceptions\UnknownSynsetOffsetException
	 */
	public function testGetSynsetUnknown(): void
	{
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

		static::assertEnum($partOfSpeech, $synset->getPartOfSpeech());
	}

	/**
	 * @expectedException \UnexpectedValueException
	 * @expectedExceptionMessage Repository for ADVERB is not registered.
	 */
	public function testFindSynsetByPartOfSpeechUnknownPartOfSpeech(): void
	{
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

	/**
	 * @expectedException \UnexpectedValueException
	 * @expectedExceptionMessage Repository for ADVERB is not registered.
	 */
	public function testGetSynsetByPartOfSpeechUnknownPartOfSpeech(): void
	{
		$repository = new SynsetMultiRepository();
		$repository->getSynsetByPartOfSpeech(PartOfSpeechEnum::ADVERB(), 100);
	}

	/**
	 * @expectedException \AL\PhpWndb\Exceptions\UnknownSynsetOffsetException
	 */
	public function testGetSynsetByPartOfSpeechUnknownSynset(): void
	{
		$repository = $this->createMultiRepository();
		$repository->getSynsetByPartOfSpeech(PartOfSpeechEnum::VERB(), -100);
	}


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
