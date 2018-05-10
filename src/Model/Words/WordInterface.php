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
	public function getAllRelated(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getAntonyms(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getDomainOfSynsetTopics(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getDomainOfSynsetRegions(): array;

	/**
	 * @return RelationPointerInterface[]
	 */
	public function getDomainOfSynsetUsages(): array;
}
