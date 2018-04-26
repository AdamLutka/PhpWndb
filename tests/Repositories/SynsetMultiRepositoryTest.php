<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Repositories;

use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\SynsetMultiRepository;
use AL\PhpWndb\Repositories\SynsetRepositoryInterface;
use AL\PhpWndb\Tests\BaseTestAbstract;

class SynsetMultiRepositoryTest extends BaseTestAbstract
{
	/**
	 * @dataProvider dpTestGetSynsetByOffset
	 */
	public function testGetSynsetByOffset(PartOfSpeechEnum $partOfSpeech): void
	{
		$repository = new SynsetMultiRepository();
		$repository->addRepository(PartOfSpeechEnum::ADJECTIVE(), $this->createRepository(PartOfSpeechEnum::ADJECTIVE()));
		$repository->addRepository(PartOfSpeechEnum::ADVERB(), $this->createRepository(PartOfSpeechEnum::ADVERB()));
		$repository->addRepository(PartOfSpeechEnum::NOUN(), $this->createRepository(PartOfSpeechEnum::NOUN()));
		$repository->addRepository(PartOfSpeechEnum::VERB(), $this->createRepository(PartOfSpeechEnum::VERB()));

		$synset = $repository->getSynsetByOffset($partOfSpeech, 1000);
		static::assertEnum($partOfSpeech, $synset->getPartOfSpeech());
	}

	public function dpTestGetSynsetByOffset(): array
	{
		return [
			[PartOfSpeechEnum::ADJECTIVE()],
			[PartOfSpeechEnum::ADVERB()],
			[PartOfSpeechEnum::NOUN()],
			[PartOfSpeechEnum::VERB()],
		];
	}

	/**
	 * @expectedException \UnexpectedValueException
	 * @expectedExceptionMessage Repository for NOUN is not registered.
	 */
	public function testGetSynsetByOffsetUnknown(): void
	{
		$repository = new SynsetMultiRepository();
		$repository->getSynsetByOffset(PartOfSpeechEnum::NOUN(), 100);
	}


	protected function createRepository(PartOfSpeechEnum $partOfSpeech): SynsetRepositoryInterface
	{
		$synset = $this->createMock(SynsetInterface::class);
		$synset->method('getPartOfSpeech')->willReturn($partOfSpeech);

		$repository = $this->createMock(SynsetRepositoryInterface::class);
		$repository->method('getSynset')->willReturn($synset);

		return $repository;
	}
}
