<?php
declare(strict_types=1);

namespace AL\PhpWndb\Repositories;

use AL\PhpWndb\Model\Indexes\WordIndexInterface;

class WordIndexMultiRepository implements WordIndexRepositoryInterface
{
	/** @var WordIndexRepositoryInterface[] */
	protected $repositories;


	/**
	 * @param WordIndexRepositoryInterface[] $repositories
	 */
	public function __construct(iterable $repositories)
	{
		$this->repositories = $repositories;
	}


	public function findWordIndex(string $lemma): ?WordIndexInterface
	{
		foreach ($this->repositories as $repository) {
			$wordIndex = $repository->findWordIndex($lemma);
			if ($wordIndex !== null) {
				return $wordIndex;
			}
		}

		return null;
	}

	public function dispose(WordIndexInterface $wordIndex): void
	{
		foreach ($this->repositories as $repository) {
			$repository->dispose($wordIndex);
		}
	}
}
