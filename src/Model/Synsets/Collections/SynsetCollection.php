<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Collections;

use AL\PhpWndb\Model\Synsets\Adjectives\SynsetAdjectivesInterface;
use AL\PhpWndb\Model\Synsets\Adverbs\SynsetAdverbsInterface;
use AL\PhpWndb\Model\Synsets\Nouns\SynsetNounsInterface;
use AL\PhpWndb\Model\Synsets\Verbs\SynsetVerbsInterface;
use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\Model\Indices\Collections\WordIndexCollectionInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\SynsetMultiRepositoryInterface;

class SynsetCollection implements SynsetCollectionInterface
{
	/** @var SynsetMultiRepositoryInterface */
	protected $synsetMultiRepository;

	/** @var WordIndexCollectionInterface */
	protected $wordIndexCollection;


	public function __construct(
		SynsetMultiRepositoryInterface $synsetMultiRepository,
		WordIndexCollectionInterface $wordIndexCollection
	) {
		$this->synsetMultiRepository = $synsetMultiRepository;
		$this->wordIndexCollection = $wordIndexCollection;
	}


	public function getSynsetAdjectives(): array
	{
		$offsets = ($wordIndex = $this->wordIndexCollection->getAdjectiveWordIndex()) ? $wordIndex->getSynsetOffsets() : [];
		/** @var SynsetAdjectivesInterface[] */
		return $this->offsetsToSynsets(PartOfSpeechEnum::ADJECTIVE(), $offsets);
	}

	public function getSynsetAdverbs(): array
	{
		$offsets = ($wordIndex = $this->wordIndexCollection->getAdverbWordIndex()) ? $wordIndex->getSynsetOffsets() : [];
		/** @var SynsetAdverbsInterface[] */
		return $this->offsetsToSynsets(PartOfSpeechEnum::ADVERB(), $offsets);
	}

	public function getSynsetNouns(): array
	{
		$offsets = ($wordIndex = $this->wordIndexCollection->getNounWordIndex()) ? $wordIndex->getSynsetOffsets() : [];
		/** @var SynsetNounsInterface[] */
		return $this->offsetsToSynsets(PartOfSpeechEnum::NOUN(), $offsets);
	}

	public function getSynsetVerbs(): array
	{
		$offsets = ($wordIndex = $this->wordIndexCollection->getVerbWordIndex()) ? $wordIndex->getSynsetOffsets() : [];
		/** @var SynsetVerbsInterface[] */
		return $this->offsetsToSynsets(PartOfSpeechEnum::VERB(), $offsets);
	}


	public function getAllSynsets(): array
	{
		return array_merge(
			$this->getSynsetNouns(),
			$this->getSynsetVerbs(),
			$this->getSynsetAdjectives(),
			$this->getSynsetAdverbs()
		);
	}


	/**
	 * @param int[] $offsets
	 * @return SynsetInterface[]
	 */
	protected function offsetsToSynsets(PartOfSpeechEnum $partOfSpeech, array $offsets): array
	{
		$synsets = [];
		foreach ($offsets as $offset) {
			$synsets[] = $this->synsetMultiRepository->getSynsetByPartOfSpeech($partOfSpeech, $offset);
		}

		return $synsets;
	}
}
