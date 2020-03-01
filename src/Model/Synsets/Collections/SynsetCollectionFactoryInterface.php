<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Collections;

interface SynsetCollectionFactoryInterface
{
	/**
	 * @param int[] $synsetAdjectiveOffsets
	 * @param int[] $synsetAdverbOffsets
	 * @param int[] $synsetNounOffsets
	 * @param int[] $synsetVerbOffsets
	 */
	public function createSynsetCollection(
		array $synsetAdjectiveOffsets,
		array $synsetAdverbOffsets,
		array $synsetNounOffsets,
		array $synsetVerbOffsets
	): SynsetCollectionInterface;
}
