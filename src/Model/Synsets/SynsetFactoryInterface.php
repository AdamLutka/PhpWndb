<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets;

use AL\PhpWndb\Parsing\ParsedData\ParsedSynsetDataInterface;

interface SynsetFactoryInterface
{
	public function createSynsetFromParseData(ParsedSynsetDataInterface $parsedSynsetData): SynsetInterface;
}
