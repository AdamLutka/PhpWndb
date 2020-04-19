<?php
declare(strict_types=1);

namespace AL\PhpWndb\Repositories;

use AL\PhpWndb\Model\Indices\WordIndexInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use UnexpectedValueException;

class WordIndexMultiRepository implements WordIndexMultiRepositoryInterface
{
	/** @var WordIndexRepositoryInterface[] */
	protected $repositories = [];


	public function addRepository(PartOfSpeechEnum $partOfSpeech, WordIndexRepositoryInterface $repository): void
	{
		$this->repositories[(string)$partOfSpeech] = $repository;
	}


	/**
	 * @return WordIndexInterface[]
	 */
	public function findAllWordIndices(string $lemma): array
	{
		$wordIndicies = [];

		foreach ($this->repositories as $repository) {
			$wordIndex = $repository->findWordIndex($lemma);
			if ($wordIndex !== null) {
				$wordIndicies[] = $wordIndex;
			}
		}

		return $wordIndicies;
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

	public function findWordIndexByPartOfSpeech(PartOfSpeechEnum $partOfSpeech, string $lemma): ?WordIndexInterface
	{
		$key = (string)$partOfSpeech;
		if (!isset($this->repositories[$key])) {
			throw new UnexpectedValueException("Repository for $partOfSpeech is not registered.");
		}

		return $this->repositories[$key]->findWordIndex($lemma);
	}


	public function dispose(WordIndexInterface $wordIndex): void
	{
		foreach ($this->repositories as $repository) {
			$repository->dispose($wordIndex);
		}
	}
}
