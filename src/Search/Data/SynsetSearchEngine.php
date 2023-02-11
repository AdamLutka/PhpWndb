<?php

declare(strict_types=1);

namespace AL\PhpWndb\Search\Data;

use AL\PhpWndb\Model\Data\RelationPointer;
use AL\PhpWndb\Model\Data\Synset;
use AL\PhpWndb\Model\SynsetId\SynsetId;
use AL\PhpWndb\Repository\Data\SynsetRepository;

class SynsetSearchEngine
{
    public function __construct(
        protected readonly SynsetRepository $synsetRepository,
    ) {
    }

    /**
     * @param SynsetId[] $synsetIds
     * @return Synset[]
     */
    public function listBySynsetIds(array $synsetIds): array
    {
        $synsets = [];
        foreach ($synsetIds as $synsetId) {
            $synsets[] = $this->synsetRepository->getSynset(
                $synsetId->getSyntacticCategory(),
                $synsetId->getSynsetOffset(),
            );
        }

        return $synsets;
    }

    /**
     * @param RelationPointer[] $relationPointers
     * @return Synset[]
     */
    public function listByRelations(array $relationPointers): array
    {
        return \array_map(
            fn (RelationPointer $pointer) => $this->getByRelation($pointer),
            $relationPointers,
        );
    }

    public function getByRelation(RelationPointer $relationPointer): Synset
    {
        return $this->synsetRepository->getSynset(
            $relationPointer->getSynsetSyntacticCategory(),
            $relationPointer->getSynsetOffset(),
        );
    }
}
