<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets\Collections;

use AL\PhpWndb\Model\Synsets\Adjectives\SynsetAdjectivesInterface;
use AL\PhpWndb\Model\Synsets\Adverbs\SynsetAdverbsInterface;
use AL\PhpWndb\Model\Synsets\Nouns\SynsetNounsInterface;
use AL\PhpWndb\Model\Synsets\Verbs\SynsetVerbsInterface;
use AL\PhpWndb\Model\Synsets\SynsetInterface;

interface SynsetCollectionInterface
{
	/**
	 * @return SynsetAdjectivesInterface[]
	 */
	public function getSynsetAdjectives(): array;

	/**
	 * @return SynsetAdverbsInterface[]
	 */
	public function getSynsetAdverbs(): array;

	/**
	 * @return SynsetNounsInterface[]
	 */
	public function getSynsetNouns(): array;

	/**
	 * @return SynsetVerbsInterface[]
	 */
	public function getSynsetVerbs(): array;


	/**
	 * @return SynsetInterface[]
	 */
	public function getAllSynsets(): array;
}
