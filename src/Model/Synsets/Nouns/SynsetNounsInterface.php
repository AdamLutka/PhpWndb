<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Nouns;

use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\Model\Words\NounInterface;

interface SynsetNounsInterface extends SynsetInterface
{
	/**
	 * @return NounInterface[]
	 */
	public function getNouns(): array;

	public function getSynsetCategory(): SynsetNounsCategoryEnum;
}
