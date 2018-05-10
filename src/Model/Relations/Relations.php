<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Relations;

class Relations implements RelationsInterface
{
	/** @var RelationPointerInterface[][]  */
	protected $pointers = [];


	public function addRelationPointer(RelationPointerInterface $pointer): void
	{
		$this->pointers[(string)$pointer->getPointerType()][] = $pointer;
	}

	/**
	 * @param RelationPointerInterface[] $pointers
	 */
	public function addRelationPointers(array $pointers): void
	{
		foreach ($pointers as $pointer) {
			$this->addRelationPointer($pointer);
		}
	}

	public function getRelationPointersOfType(RelationPointerTypeEnum $pointerType): array
	{
		return $this->pointers[(string)$pointerType] ?? [];
	}

	public function getAllRelationPointers(): array
	{
		return array_reduce($this->pointers, function ($carry, $item) {
			return array_merge($carry, $item);
		}, []);
	}
}
