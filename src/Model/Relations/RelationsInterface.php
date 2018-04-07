<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Relations;

interface RelationsInterface
{
	/**
	 * @return RelationPointerInterface[]
	 */
	public function getRelationPointersOfType(RelationPointerTypeEnum $pointerType): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAllRelationPointers(): iterable;
}
