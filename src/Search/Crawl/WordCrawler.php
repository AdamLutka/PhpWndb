<?php

declare(strict_types=1);

namespace AL\PhpWndb\Search\Crawl;

use AL\PhpWndb\Model\Data\Synset;
use AL\PhpWndb\Model\Data\Word;
use AL\PhpWndb\Model\RelationPointerType;
use AL\PhpWndb\Search\Data\SynsetSearchEngine;
use LogicException;

class WordCrawler
{
    public function __construct(
        protected readonly int $wordIndex,
        protected readonly Synset $synset,
        protected readonly SynsetSearchEngine $synsetSearchEngine,
        protected readonly WordCrawlerFactory $wordCrawlerFactory,
        protected readonly WordListCrawlerFactory $wordListCrawlerFactory,
    ) {
    }

    public function moveTo(RelationPointerType $pointerType): WordListCrawler
    {
        $wordCrawlers = [];
        foreach ($this->synset->getRelationPointers() as $pointer) {
            if (
                $pointer->getSourceSynsetWordIndex() !== $this->wordIndex
                || $pointer->getTargetSynsetWordIndex() === null
                || $pointer->getType() !== $pointerType
            ) {
                continue;
            }

            $synset = $this->synsetSearchEngine->getByRelation($pointer);

            $wordCrawlers[] = $this->wordCrawlerFactory->create($pointer->getTargetSynsetWordIndex(), $synset);
        }

        return $this->wordListCrawlerFactory->create($wordCrawlers);
    }

    public function toString(): string
    {
        return $this->getWord()->getValue();
    }

    protected function getWord(): Word
    {
        return $this->synset->getWords()[$this->wordIndex]
            ?? throw new LogicException(
                "Synset `{$this->synset->getOffset()}` doesn't contains word with index `{$this->wordIndex}`.",
            );
    }
}
