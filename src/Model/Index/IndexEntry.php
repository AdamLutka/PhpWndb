<?php

declare(strict_types=1);

namespace AL\PhpWndb\Model\Index;

use AL\PhpWndb\Model\RelationPointerType;

class IndexEntry
{
    /**
     * @param RelationPointerType[] $relationPointerTypes
     * @param int[] $synsetOffsets
     */
    public function __construct(
        protected readonly SyntacticCategory $syntacticCategory,
        protected readonly array $relationPointerTypes,
        protected readonly array $synsetOffsets,
    ) {
    }

    public function getSyntacticCategory(): SyntacticCategory
    {
        return $this->syntacticCategory;
    }

    /**
     * @return RelationPointerType[]
     */
    public function getRelationPointerTypes(): array
    {
        return $this->relationPointerTypes;
    }

    /**
     * @return int[]
     */
    public function getSynsetOffsets(): array
    {
        return $this->synsetOffsets;
    }
}
