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
	public function testFindAllWordIndices(): void
	{
		$nounWordIndex = $this->createWordIndex();
		$verbWordIndex = $this->createWordIndex();
		$multiRepository = $this->createMultiRepository(['noun' => $nounWordIndex, 'verb' => $verbWordIndex]);

		static::assertSame(
			[$nounWordIndex, $verbWordIndex],
			$multiRepository->findAllWordIndices('lemma')
		);
	}

	public function testFindWordIndexFound(): void
	{
		$wordIndex = $this->createWordIndex();
		$multiRepository = $this->createMultiRepository(['verb' => $wordIndex]);

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
		$multiRepository = $this->createMultiRepository(['adverb' => $wordIndex]);

		static::assertSame($wordIndex, $multiRepository->findWordIndexByPartOfSpeech(PartOfSpeechEnum::ADVERB(), 'lemma'));
	}

	public function testFindWordIndexByPartOfSpeechNotFound(): void
	{
		$wordIndex = $this->createWordIndex();
		$multiRepository = $this->createMultiRepository(['verb' => $wordIndex]);

		static::assertNull($multiRepository->findWordIndexByPartOfSpeech(PartOfSpeechEnum::NOUN(), 'lemma'));
	}


	/**
	 * @param WordIndexInterface[] $foundWordIndices
	 */
	protected function createMultiRepository(array $foundWordIndices = []): WordIndexMultiRepository
	{
		$multiRepository = new WordIndexMultiRepository();
		$multiRepository->addRepository(PartOfSpeechEnum::NOUN(),      $this->createRepository($foundWordIndices['noun']      ?? null));
		$multiRepository->addRepository(PartOfSpeechEnum::VERB(),      $this->createRepository($foundWordIndices['verb']      ?? null));
		$multiRepository->addRepository(PartOfSpeechEnum::ADVERB(),    $this->createRepository($foundWordIndices['adverb']    ?? null));
		$multiRepository->addRepository(PartOfSpeechEnum::ADJECTIVE(), $this->createRepository($foundWordIndices['adjective'] ?? null));

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
