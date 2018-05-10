<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

interface ParsedWordDataInterface
{
	public function getValue(): string;

	public function getLexId(): int;
}
