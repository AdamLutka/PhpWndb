<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Words;

use AL\PhpWndb\Model\Relations\RelationPointerInterface;

interface WordInterface
{
	public function getLemma(): string;

	public function getLexId(): int;


	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAllRelated(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAntonyms(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getDomainOfSynsetTopics(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getDomainOfSynsetRegions(): iterable;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getDomainOfSynsetUsages(): iterable;
}
