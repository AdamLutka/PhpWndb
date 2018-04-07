<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Adverbs;

use AL\PhpWndb\Model\Synsets\SynsetAbstract;
use AL\PhpWndb\Model\Words\AdverbInterface;

class SynsetAdverbs extends SynsetAbstract implements SynsetAdverbsInterface
{
	/** @var SynsetAdverbsCategoryEnum */
	protected $synsetCategory;


	/**
	 * @param AdverbInterface[] $words
	 */
	public function __construct(int $synsetOffset, string $gloss, iterable $words, SynsetAdverbsCategoryEnum $synsetCategory)
	{
		parent::__construct($synsetOffset, $gloss, $words);
		$this->synsetCategory = $synsetCategory;
	}


	public function getAdverbs(): iterable
	{
		return $this->words;
	}

	public function getSynsetCategory(): SynsetAdverbsCategoryEnum
	{
		return $this->synsetCategory;
	}
}
