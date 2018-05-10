<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Adverbs;

use AL\PhpWndb\Model\Synsets\SynsetInterface;
use AL\PhpWndb\Model\Words\AdverbInterface;

interface SynsetAdverbsInterface extends SynsetInterface
{
	/**
	 * @return AdverbInterface[]
	 */
	public function getAdverbs(): array;

	public function getSynsetCategory(): SynsetAdverbsCategoryEnum;
}
