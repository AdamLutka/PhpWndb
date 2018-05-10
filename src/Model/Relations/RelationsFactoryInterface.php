<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Relations;

interface RelationsFactoryInterface
{
	/**
	 * @param RelationPointerInterface[] $relationPointers
	 */
	public function createRelations(array $relationPointers): RelationsInterface;
}
