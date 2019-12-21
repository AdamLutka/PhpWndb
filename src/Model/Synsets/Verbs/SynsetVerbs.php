<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Verbs;

use AL\PhpWndb\Model\Synsets\SynsetAbstract;
use AL\PhpWndb\Model\Words\VerbInterface;
use AL\PhpWndb\PartOfSpeechEnum;

class SynsetVerbs extends SynsetAbstract implements SynsetVerbsInterface
{
	/** @var SynsetVerbsCategoryEnum */
	protected $synsetCategory;


	/**
	 * @param VerbInterface[] $words
	 */
	public function __construct(int $synsetOffset, string $gloss, array $words, SynsetVerbsCategoryEnum $synsetCategory)
	{
		parent::__construct($synsetOffset, $gloss, $words);
		$this->synsetCategory = $synsetCategory;
	}


	public function getVerbs(): array
	{
		/** @var VerbInterface[] */
		return $this->words;
	}

	public function getSynsetCategory(): SynsetVerbsCategoryEnum
	{
		return $this->synsetCategory;
	}

	public function getPartOfSpeech(): PartOfSpeechEnum
	{
		return PartOfSpeechEnum::VERB();
	}
}
