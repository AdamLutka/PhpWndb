<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerInterface;

interface NounInterface extends WordInterface
{
	/**
	 * @return RelationPointerInterface[]
	 */
	public function getHypernyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getInstanceHypernyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getHyponyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getInstanceHyponyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getMemberHolonyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getSubstanceHolonyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getPartHolonyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getMemberMeronyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getSubstanceMeronyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getPartMeronyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAttributes(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getDerivationallyRelatedForms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getMemberOfThisDomainTopics(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getMemberOfThisDomainRegions(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getMemberOfThisDomainUsages(): array;
}
