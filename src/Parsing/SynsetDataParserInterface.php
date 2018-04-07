<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing;

use AL\PhpWndb\Parsing\Exceptions\SynsetDataParseException;
use AL\PhpWndb\Parsing\ParsedData\ParsedSynsetDataInterface;

interface SynsetDataParserInterface
{
	/**
	 * @throws SynsetDataParseException
	 */
	public function parseSynsetData(string $synsetData): ParsedSynsetDataInterface;
}