<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerInterface;

interface AdverbInterface extends WordInterface
{
	/**
	 * @return RelationPointerInterface[]
	 */
	public function getDerivedFromAdjectives(): array;
}
