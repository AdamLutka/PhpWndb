<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerInterface;

interface VerbInterface extends WordInterface
{
	/**
	 * @return RelationPointerInterface[]
	 */
	public function getHypernyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getHyponyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getEntailments(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getCauses(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAlsoSee(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getVerbGroups(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getDerivationallyRelatedForms(): iterable;


	/**
	 * @return int[]
	 */
	public function getFrames(): iterable;
}
