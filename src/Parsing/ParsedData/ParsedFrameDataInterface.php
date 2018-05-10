<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

interface ParsedFrameDataInterface
{
	public function getFrameNumber(): int;

	public function getWordIndex(): int;
}
