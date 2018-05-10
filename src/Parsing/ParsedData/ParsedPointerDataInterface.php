<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

interface ParsedPointerDataInterface
{
	public function getPointerType(): string;

	public function getSynsetOffset(): int;

	public function getPartOfSpeech(): string;

	public function getSourceWordIndex(): int;

	public function getTargetWordIndex(): int;
}
