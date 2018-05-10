<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerInterface;

interface AdjectiveInterface extends WordInterface
{
	/**
	 * @return RelationPointerInterface[]
	 */
	public function getSimilarTo(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getParticipleOfVerbs(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getPertainyms(): array;
	
	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAttributes(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAlsoSee(): array;
}
