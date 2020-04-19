<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Indices;

use AL\PhpWndb\Parsing\ParsedData\ParsedWordIndexInterface;

interface WordIndexFactoryInterface
{
	public function createWordIndexFromParsedData(ParsedWordIndexInterface $parsedWordIndex): WordIndexInterface;
}
