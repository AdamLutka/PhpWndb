<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Repositories;

use AL\PhpWndb\Model\Indexes\WordIndexInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\WordIndexMultiRepository;
use AL\PhpWndb\Repositories\WordIndexRepositoryInterface;
use AL\PhpWndb\Tests\BaseTestAbstract;

class WordIndexMultiRepositoryTest extends BaseTestAbstract
{
	public function testFindWordIndexFound(): void
	{
		$wordIndex = $this->createWordIndex();
		$multiRepository = $this->createMultiRepository($wordIndex);

		static::assertSame($wordIndex, $multiRepository->findWordIndex('lemma'));
	}

	public function testFindWordIndexNotFound(): void
	{
		$multiRepository = $this->createMultiRepository();

		static::assertNull($multiRepository->findWordIndex('lemma'));
	}


	public function testFindWordIndexByPartOfSpeechFound(): void
	{
		$wordIndex = $this->createWordIndex();
		$multiRepository = $this->createMultiRepository($wordIndex);

		static::assertSame($wordIndex, $multiRepository->findWordIndexByPartOfSpeech(PartOfSpeechEnum::ADVERB(), 'lemma'));
	}

	public function testFindWordIndexByPartOfSpeechNotFound(): void
	{
		$multiRepository = $this->createMultiRepository();

		static::assertNull($multiRepository->findWordIndexByPartOfSpeech(PartOfSpeechEnum::NOUN(), 'lemma'));
	}


	protected function createMultiRepository(?WordIndexInterface $foundWordIndex = null): WordIndexMultiRepository
	{
		$multiRepository = new WordIndexMultiRepository();
		$multiRepository->addRepository(PartOfSpeechEnum::NOUN(), $this->createRepository());
		$multiRepository->addRepository(PartOfSpeechEnum::VERB(), $this->createRepository());
		$multiRepository->addRepository(PartOfSpeechEnum::ADVERB(), $this->createRepository($foundWordIndex));

		return $multiRepository;
	}

	protected function createRepository(?WordIndexInterface $foundWordIndex = null): WordIndexRepositoryInterface
	{
		$repository = $this->createMock(WordIndexRepositoryInterface::class);
		$repository->method('findWordIndex')->willReturn($foundWordIndex);

		return $repository;
	}

	protected function createWordIndex(): WordIndexInterface
	{
		return $this->createMock(WordIndexInterface::class);
	}
}
