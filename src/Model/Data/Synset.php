<?php

declare(strict_types=1);

namespace AL\PhpWndb\Model\Data;

class Synset
{
    /**
     * @param Word[] $words
     * @param RelationPointer[] $relationPointers
     */
    public function __construct(
        protected readonly int $offset,
        protected readonly SynsetType $type,
        protected readonly array $words,
        protected readonly array $relationPointers,
        protected readonly string $gloss,
    ) {
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getType(): SynsetType
    {
        return $this->type;
    }

    /**
     * @return Word[]
     */
    public function getWords(): array
    {
        return $this->words;
    }

    /**
     * @return RelationPointer[]
     */
    public function getRelationPointers(): array
    {
        return $this->relationPointers;
    }

    public function getGloss(): string
    {
        return $this->gloss;
    }
}
