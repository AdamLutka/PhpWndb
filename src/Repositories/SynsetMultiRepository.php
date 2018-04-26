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

	/**
	 * @throws UnexpectedValueException
	 * @throws UnknownSynsetOffsetException
	 */
	public function getSynsetByOffset(PartOfSpeechEnum $partOfSpeech, int $synsetOffset): SynsetInterface
	{
		$repository = $this->getRepository($partOfSpeech);
		if ($repository === null) {
			throw new UnexpectedValueException("Repository for $partOfSpeech is not registered.");
		}

		return $repository->getSynset($synsetOffset);
	}

	public function dispose(SynsetInterface $synset): void
	{
		$repository = $this->getRepository($synset->getPartOfSpeech());
		if ($repository !== null) {
			$repository->dispose($synset);
		}
	}


	private function getRepository(PartOfSpeechEnum $partOfSpeech): ?SynsetRepositoryInterface
	{
		return $this->repositories[(string)$partOfSpeech] ?? null;
	}
}
