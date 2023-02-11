<?php

declare(strict_types=1);

namespace AL\PhpWndb\Parser;

use AL\PhpWndb\Model\Data\SynsetType;
use AL\PhpWndb\Parser\Exception\ParseException;

class SynsetTypeMapper
{
    /**
     * @throws ParseException
     */
    public function mapSynsetType(string $synsetType): SynsetType
    {
        return match ($synsetType) {
            'n' => SynsetType::NOUN,
            'v' => SynsetType::VERB,
            'a' => SynsetType::ADJECTIVE,
            's' => SynsetType::ADJECTIVE_SATELLITE,
            'r' => SynsetType::ADVERB,
            default => throw new ParseException("Unknown synset type `{$synsetType}`."),
        };
    }
}
