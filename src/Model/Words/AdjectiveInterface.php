<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerInterface;

interface AdjectiveInterface extends WordInterface
{
	/**
	 * @return RelationPointerInterface[]
	 */
	public function getSimilarTo(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getParticipleOfVerbs(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getPertainyms(): iterable;
	
	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAttributes(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAlsoSee(): iterable;
}
