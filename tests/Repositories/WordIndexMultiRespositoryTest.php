<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\Repositories;

use AL\PhpWndb\Model\Indexes\WordIndexInterface;
use AL\PhpWndb\Repositories\WordIndexMultiRepository;
use AL\PhpWndb\Repositories\WordIndexRepositoryInterface;
use AL\PhpWndb\Tests\BaseTestAbstract;

class WordIndexMultiRepositoryTest extends BaseTestAbstract
{
	public function testFindWordIndexFound(): void
	{
		$wordIndex = $this->createMock(WordIndexInterface::class);
		$multiRepository = new WordIndexMultiRepository([
			$this->createRepository(),
			$this->createRepository(),
			$this->createRepository($wordIndex),
		]);

		static::assertSame($wordIndex, $multiRepository->findWordIndex('lemma'));
	}

	public function testFindWordIndexNotFound(): void
	{
		$multiRepository = new WordIndexMultiRepository([
			$this->createRepository(),
			$this->createRepository(),
			$this->createRepository(),
		]);

		static::assertNull($multiRepository->findWordIndex('lemma'));
	}


	protected function createRepository(?WordIndexInterface $foundWordIndex = null): WordIndexRepositoryInterface
	{
		$repository = $this->createMock(WordIndexRepositoryInterface::class);
		$repository->method('findWordIndex')->willReturn($foundWordIndex);

		return $repository;
	}
}
