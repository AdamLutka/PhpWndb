<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

interface ParsedSynsetDataInterface
{
	public function getSynsetOffset(): ?int;

	public function getLexFileNumber(): ?int;

	public function getPartOfSpeech(): ?string;

	public function getGloss(): ?string;

	/**
	 * @return ParsedWordDataInterface[]
	 */
	public function getWords(): iterable;

	/**
	 * @return ParsedPointerDataInterface[]
	 */
	public function getPointers(): iterable;

	/**
	 * @return ParsedFrameDataInterface[]
	 */
	public function getFrames(): iterable;
}
