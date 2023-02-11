<?php

declare(strict_types=1);

namespace AL\PhpWndb\Model\Data;

use AL\PhpWndb\Model\Index\SyntacticCategory;
use AL\PhpWndb\Model\RelationPointerType;

class RelationPointerFactory
{
    public function create(
        RelationPointerType $type,
        int $synsetOffset,
        SyntacticCategory $synsetSyntacticCategory,
        ?int $sourceSynsetWordIndex,
        ?int $targetSynsetWordIndex,
    ): RelationPointer {
        return new RelationPointer(
            type: $type,
            synsetOffset: $synsetOffset,
            synsetSyntacticCategory: $synsetSyntacticCategory,
            sourceSynsetWordIndex: $sourceSynsetWordIndex,
            targetSynsetWordIndex: $targetSynsetWordIndex,
        );
    }
}
