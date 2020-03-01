<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Collections;

use AL\PhpWndb\Model\Synsets\Adjectives\SynsetAdjectivesInterface;
use AL\PhpWndb\Model\Synsets\Adverbs\SynsetAdverbsInterface;
use AL\PhpWndb\Model\Synsets\Nouns\SynsetNounsInterface;
use AL\PhpWndb\Model\Synsets\Verbs\SynsetVerbsInterface;
use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Repositories\SynsetMultiRepositoryInterface;

class SynsetCollection implements SynsetCollectionInterface
{
	/** @var SynsetMultiRepositoryInterface */
	protected $synsetMultiRepository;

	/** @var int[] */
	protected $synsetAdjectiveOffsets;

	/** @var int[] */
	protected $synsetAdverbOffsets;

	/** @var int[] */
	protected $synsetNounOffsets;

	/** @var int[] */
	protected $synsetVerbOffsets;


	/**
	 * @param int[] $synsetAdjectiveOffsets
	 * @param int[] $synsetAdverbOffsets
	 * @param int[] $synsetNounOffsets
	 * @param int[] $synsetVerbOffsets
	 */
	public function __construct(
		SynsetMultiRepositoryInterface $synsetMultiRepository,
		array $synsetAdjectiveOffsets,
		array $synsetAdverbOffsets,
		array $synsetNounOffsets,
		array $synsetVerbOffsets
	) {
		$this->synsetMultiRepository = $synsetMultiRepository;
		$this->synsetAdjectiveOffsets = $synsetAdjectiveOffsets;
		$this->synsetAdverbOffsets = $synsetAdverbOffsets;
		$this->synsetNounOffsets = $synsetNounOffsets;
		$this->synsetVerbOffsets = $synsetVerbOffsets;
	}


	public function getSynsetAdjectives(): array
	{
		/** @var SynsetAdjectivesInterface[] */
		return $this->offsetsToSynsets(PartOfSpeechEnum::ADJECTIVE(), $this->synsetAdjectiveOffsets);
	}

	public function getSynsetAdverbs(): array
	{
		/** @var SynsetAdverbsInterface[] */
		return $this->offsetsToSynsets(PartOfSpeechEnum::ADVERB(), $this->synsetAdverbOffsets);
	}

	public function getSynsetNouns(): array
	{
		/** @var SynsetNounsInterface[] */
		return $this->offsetsToSynsets(PartOfSpeechEnum::NOUN(), $this->synsetNounOffsets);
	}

	public function getSynsetVerbs(): array
	{
		/** @var SynsetVerbsInterface[] */
		return $this->offsetsToSynsets(PartOfSpeechEnum::VERB(), $this->synsetVerbOffsets);
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
