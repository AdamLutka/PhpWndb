<?php

declare(strict_types=1);

namespace AL\PhpWndb\Model\Data;

class SynsetFactory
{
    /**
     * @param Word[] $words
     * @param RelationPointer[] $relationPointers
     */
    public function create(
        int $offset,
        SynsetType $type,
        array $words,
        array $relationPointers,
        string $gloss,
    ): Synset {
        return new Synset($offset, $type, $words, $relationPointers, $gloss);
    }
}
