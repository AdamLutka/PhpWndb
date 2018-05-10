<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

interface ParsedWordIndexInterface
{
	public function getLemma(): string;

	public function getPartOfSpeech(): string;

	/**
	 * @return string[]
	 */
	public function getPointerTypes(): array;

	/**
	 * @return int[]
	 */
	public function getSynsetOffsets(): array;
}
