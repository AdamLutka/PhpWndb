<?php

declare(strict_types=1);

namespace AL\PhpWndb\Search\Crawl;

use AL\PhpWndb\Model\Data\RelationPointer;
use AL\PhpWndb\Model\Data\Synset;
use AL\PhpWndb\Model\Data\SynsetType;
use AL\PhpWndb\Model\RelationPointerType;
use AL\PhpWndb\Search\Data\SynsetSearchEngine;

/**
 * @extends ArrayCrawler<WordCrawler>
 */
class SynsetCrawler extends ArrayCrawler
{
    public function __construct(
        protected readonly Synset $synset,
        protected readonly WordCrawlerFactory $wordCrawlerFactory,
        protected readonly SynsetListCrawlerFactory $synsetListCrawlerFactory,
        protected readonly SynsetSearchEngine $synsetSearchEngine,
    ) {
        parent::__construct(
            $this->wordCrawlerFactory->createAllFromSynset($this->synset),
        );
    }

    public function moveTo(RelationPointerType $pointerType): SynsetListCrawler
    {
        $pointers = \array_filter(
            $this->synset->getRelationPointers(),
            static fn (RelationPointer $pointer)
                => $pointer->getSourceSynsetWordIndex() === null && $pointer->getType() === $pointerType,
        );

        $synsets = $this->synsetSearchEngine->listByRelations($pointers);

        return $this->synsetListCrawlerFactory->createFromSynsets($synsets);
    }

    public function getType(): SynsetType
    {
        return $this->synset->getType();
    }

    public function getGloss(): string
    {
        return $this->synset->getGloss();
    }
}
