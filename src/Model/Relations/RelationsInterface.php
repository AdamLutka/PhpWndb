<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Relations;

interface RelationsInterface
{
	/**
	 * @return RelationPointerInterface[]
	 */
	public function getRelationPointersOfType(RelationPointerTypeEnum $pointerType): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAllRelationPointers(): array;
}
