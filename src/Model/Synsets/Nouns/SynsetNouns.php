<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Nouns;

use AL\PhpWndb\Model\Synsets\SynsetAbstract;
use AL\PhpWndb\Model\Words\NounInterface;
use AL\PhpWndb\PartOfSpeechEnum;

class SynsetNouns extends SynsetAbstract implements SynsetNounsInterface
{
	/** @var SynsetNounsCategoryEnum */
	protected $synsetCategory;


	/**
	 * @param NounInterface[] $words
	 */
	public function __construct(int $synsetOffset, string $gloss, array $words, SynsetNounsCategoryEnum $synsetCategory)
	{
		parent::__construct($synsetOffset, $gloss, $words);
		$this->synsetCategory = $synsetCategory;
	}


	public function getNouns(): array
	{
		/** @var NounInterface[] */
		return $this->words;
	}

	public function getSynsetCategory(): SynsetNounsCategoryEnum
	{
		return $this->synsetCategory;
	}

	public function getPartOfSpeech(): PartOfSpeechEnum
	{
		return PartOfSpeechEnum::NOUN();
	}
}
