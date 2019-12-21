<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Adjectives;

use AL\PhpWndb\Model\Synsets\SynsetAbstract;
use AL\PhpWndb\Model\Words\AdjectiveInterface;
use AL\PhpWndb\PartOfSpeechEnum;

class SynsetAdjectives extends SynsetAbstract implements SynsetAdjectivesInterface
{
	/** @var SynsetAdjectivesCategoryEnum */
	protected $synsetCategory;


	/**
	 * @param AdjectiveInterface[] $words
	 */
	public function __construct(int $synsetOffset, string $gloss, array $words, SynsetAdjectivesCategoryEnum $synsetCategory)
	{
		parent::__construct($synsetOffset, $gloss, $words);
		$this->synsetCategory = $synsetCategory;
	}


	public function getAdjectives(): array
	{
		/** @var AdjectiveInterface[] */
		return $this->words;
	}

	public function getSynsetCategory(): SynsetAdjectivesCategoryEnum
	{
		return $this->synsetCategory;
	}

	public function getPartOfSpeech(): PartOfSpeechEnum
	{
		return PartOfSpeechEnum::ADJECTIVE();
	}
}
