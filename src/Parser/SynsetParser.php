<?php

declare(strict_types=1);

namespace AL\PhpWndb\Parser;

use AL\PhpWndb\Model\Data\RelationPointer;
use AL\PhpWndb\Model\Data\RelationPointerFactory;
use AL\PhpWndb\Model\Data\Synset;
use AL\PhpWndb\Model\Data\SynsetFactory;
use AL\PhpWndb\Model\Data\Word;
use AL\PhpWndb\Model\Data\WordFactory;
use AL\PhpWndb\Parser\Exception\ParseException;
use AL\PhpWndb\Storage\Stream;

class SynsetParser
{
    private const GLOSS_SEPARATOR = '|';

    public function __construct(
        protected readonly TokenizerFactory $tokenizerFactory,
        protected readonly SynsetFactory $synsetFactory,
        protected readonly SynsetTypeMapper $synsetTypeMapper,
        protected readonly WordFactory $wordFactory,
        protected readonly RelationPointerFactory $relationPointerFactory,
        protected readonly RelationPointerTypeMapper $relationPointerTypeMapper,
        protected readonly SyntacticCategoryMapper $syntacticCategoryMapper,
    ) {
    }

    /**
     * @throws ParseException
     */
    public function parseSynset(Stream $stream): Synset
    {
        $tokenizer = $this->tokenizerFactory->create($stream);

        $offset = $tokenizer->readDecimalInteger();
        // ignored lex file number
        $tokenizer->readDecimalInteger();
        $synsetType = $this->synsetTypeMapper->mapSynsetType($tokenizer->readString());
        $words = $this->parseWords($tokenizer);
        $relationPointers = $this->parseRelationPointers($tokenizer);
        [, $gloss] = \explode(self::GLOSS_SEPARATOR, $tokenizer->readRestOfLine(), 2);

        return $this->synsetFactory->create($offset, $synsetType, $words, $relationPointers, \trim($gloss));
    }

    /**
     * @return Word[]
     * @throws ParseException
     */
    protected function parseWords(Tokenizer $tokenizer): array
    {
        $wordsCount = $tokenizer->readHexInteger();

        $words = [];
        for ($i = 0; $i < $wordsCount; ++$i) {
            $words[] = $this->wordFactory->create($tokenizer->readString());
            // ignored lex id
            $tokenizer->readHexInteger();
        }

        return $words;
    }

    /**
     * @return RelationPointer[]
     * @throws ParseException
     */
    protected function parseRelationPointers(Tokenizer $tokenizer): array
    {
        $pointersCount = $tokenizer->readDecimalInteger();

        $pointers = [];
        for ($i = 0; $i < $pointersCount; ++$i) {
            $pointers[] = $this->parseRelationPointer($tokenizer);
        }

        return $pointers;
    }

    /**
     * @throws ParseException
     */
    protected function parseRelationPointer(Tokenizer $tokenizer): RelationPointer
    {
        $typeToken = $tokenizer->readString();
        $synsetOffset = $tokenizer->readDecimalInteger();
        $syntacticCategory = $this->syntacticCategoryMapper->mapSyntacticCategory($tokenizer->readString());
        $sourceIndex = $tokenizer->readHexInteger(2);
        $targetIndex = $tokenizer->readHexInteger(3);

        return $this->relationPointerFactory->create(
            type: $this->relationPointerTypeMapper->mapRelationPointerType($typeToken, $syntacticCategory),
            synsetOffset: $synsetOffset,
            synsetSyntacticCategory: $syntacticCategory,
            sourceSynsetWordIndex: $sourceIndex === 0 ? null : $sourceIndex - 1,
            targetSynsetWordIndex: $targetIndex === 0 ? null : $targetIndex - 1,
        );
    }
}
