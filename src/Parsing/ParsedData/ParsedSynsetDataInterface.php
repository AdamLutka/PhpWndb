<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

interface ParsedSynsetDataInterface
{
	public function getSynsetOffset(): int;

	public function getLexFileNumber(): int;

	public function getPartOfSpeech(): string;

	public function getGloss(): string;

	/**
	 * @return ParsedWordDataInterface[]
	 */
	public function getWords(): array;

	/**
	 * @return ParsedPointerDataInterface[]
	 */
	public function getPointers(): array;

	/**
	 * @return ParsedFrameDataInterface[]
	 */
	public function getFrames(): array;
}
