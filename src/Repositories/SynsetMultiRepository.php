<?php
declare(strict_types=1);

namespace AL\PhpWndb\Repositories;

use AL\PhpWndb\Exceptions\UnknownSynsetOffsetException;
use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\SynsetRepositoryInterface;
use UnexpectedValueException;

class SynsetMultiRepository implements SynsetMultiRepositoryInterface
{
	/** @var SynsetRepositoryInterface[] */
	protected $repositories = [];


	public function addRepository(PartOfSpeechEnum $partOfSpeech, SynsetRepositoryInterface $repository): void
	{
		$this->repositories[(string)$partOfSpeech] = $repository;
	}


	public function findSynset(int $synsetOffset): ?SynsetInterface
	{
		foreach ($this->repositories as $repository) {
			$synset = $repository->findSynset($synsetOffset);
			if ($synset !== null) {
				return $synset;
			}
		}

		return null;
	}

	public function getSynset(int $synsetOffset): SynsetInterface
	{
		$synset = $this->findSynset($synsetOffset);
		if ($synset === null) {
			throw new UnknownSynsetOffsetException($synsetOffset);
		}

		return $synset;
	}


	public function findSynsetByPartOfSpeech(PartOfSpeechEnum $partOfSpeech, int $synsetOffset): ?SynsetInterface
	{
		$repository = $this->getRepository($partOfSpeech);
		return $repository->findSynset($synsetOffset);
	}

	public function getSynsetByPartOfSpeech(PartOfSpeechEnum $partOfSpeech, int $synsetOffset): SynsetInterface
	{
		$repository = $this->getRepository($partOfSpeech);
		return $repository->getSynset($synsetOffset);
	}


	public function dispose(SynsetInterface $synset): void
	{
		$repository = $this->findRepository($synset->getPartOfSpeech());
		if ($repository !== null) {
			$repository->dispose($synset);
		}
	}


	protected function findRepository(PartOfSpeechEnum $partOfSpeech): ?SynsetRepositoryInterface
	{
		return $this->repositories[(string)$partOfSpeech] ?? null;
	}

	/**
	 * @throws UnexpectedValueException
	 */
	protected function getRepository(PartOfSpeechEnum $partOfSpeech): SynsetRepositoryInterface
	{
		$repository = $this->findRepository($partOfSpeech);
		if ($repository === null) {
			throw new UnexpectedValueException("Repository for $partOfSpeech is not registered.");
		}

		return $repository;
	}
}
