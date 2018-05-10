<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Adjectives;

use AL\PhpWndb\Model\Words\AdjectiveInterface;
use AL\PhpWndb\Model\Synsets\SynsetInterface;

interface SynsetAdjectivesInterface extends SynsetInterface
{
	/**
	 * @return AdjectiveInterface[]
	 */
	public function getAdjectives(): array;

	public function getSynsetCategory(): SynsetAdjectivesCategoryEnum;
}
