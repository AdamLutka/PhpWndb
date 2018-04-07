<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Nouns;

use AL\PhpWndb\Model\Synsets\SynsetAbstract;
use AL\PhpWndb\Model\Words\NounInterface;

class SynsetNouns extends SynsetAbstract implements SynsetNounsInterface
{
	/** @var SynsetNounsCategoryEnum */
	protected $synsetCategory;


	/**
	 * @param NounInterface[] $words
	 */
	public function __construct(int $synsetOffset, string $gloss, iterable $words, SynsetNounsCategoryEnum $synsetCategory)
	{
		parent::__construct($synsetOffset, $gloss, $words);
		$this->synsetCategory = $synsetCategory;
	}


	public function getNouns(): iterable
	{
		return $this->words;
	}

	public function getSynsetCategory(): SynsetNounsCategoryEnum
	{
		return $this->synsetCategory;
	}
}
