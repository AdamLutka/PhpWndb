<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerInterface;

interface NounInterface extends WordInterface
{
	/**
	 * @return RelationPointerInterface[]
	 */
	public function getHypernyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getInstanceHypernyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getHyponyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getInstanceHyponyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getMemberHolonyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getSubstanceHolonyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getPartHolonyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getMemberMeronyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getSubstanceMeronyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getPartMeronyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAttributes(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getDerivationallyRelatedForms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getMemberOfThisDomainTopics(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getMemberOfThisDomainRegions(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getMemberOfThisDomainUsages(): iterable;
}
