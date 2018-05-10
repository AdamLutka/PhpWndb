<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerInterface;

interface VerbInterface extends WordInterface
{
	/**
	 * @return RelationPointerInterface[]
	 */
	public function getHypernyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getHyponyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getEntailments(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getCauses(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAlsoSee(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getVerbGroups(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getDerivationallyRelatedForms(): array;


	/**
	 * @return int[]
	 */
	public function getFrames(): array;
}
