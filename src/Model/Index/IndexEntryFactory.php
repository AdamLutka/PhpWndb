<?php

declare(strict_types=1);

namespace AL\PhpWndb\Model\Index;

use AL\PhpWndb\Model\RelationPointerType;

class IndexEntryFactory
{
    /**
     * @param RelationPointerType[] $relationPointerTypes
     * @param int[] $synsetOffsets
     */
    public function create(
        SyntacticCategory $syntacticCategory,
        array $relationPointerTypes,
        array $synsetOffsets,
    ): IndexEntry {
        return new IndexEntry($syntacticCategory, $relationPointerTypes, $synsetOffsets);
    }
}
