<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Verbs;

use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\Model\Words\VerbInterface;

interface SynsetVerbsInterface extends SynsetInterface
{
	/**
	 * @return VerbInterface[]
	 */
	public function getVerbs(): array;

	public function getSynsetCategory(): SynsetVerbsCategoryEnum;
}
