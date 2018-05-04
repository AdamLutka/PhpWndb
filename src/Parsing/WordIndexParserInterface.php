<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing;

use AL\PhpWndb\Parsing\Exceptions\WordIndexParseException;
use AL\PhpWndb\Parsing\ParsedData\ParsedWordIndexInterface;

interface WordIndexParserInterface
{
	/**
	 * @throws WordIndexParseException
	 */
	public function parseWordIndex(string $wordIndex): ParsedWordIndexInterface;
}
