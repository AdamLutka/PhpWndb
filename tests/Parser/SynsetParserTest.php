<?php

declare(strict_types=1);

namespace AL\PhpWndb\Tests\Parser;

use AL\PhpWndb\Model\Data\RelationPointerFactory;
use AL\PhpWndb\Model\Data\SynsetFactory;
use AL\PhpWndb\Model\Data\SynsetType;
use AL\PhpWndb\Model\Data\Word;
use AL\PhpWndb\Model\Data\WordFactory;
use AL\PhpWndb\Model\Index\SyntacticCategory;
use AL\PhpWndb\Model\RelationPointerType;
use AL\PhpWndb\Parser\RelationPointerTypeMapper;
use AL\PhpWndb\Parser\SynsetParser;
use AL\PhpWndb\Parser\SynsetTypeMapper;
use AL\PhpWndb\Parser\SyntacticCategoryMapper;
use AL\PhpWndb\Parser\TokenizerFactory;
use AL\PhpWndb\Storage\StringStream;
use Iterator;
use PHPUnit\Framework\TestCase;

class SynsetParserTest extends TestCase
{
    /**
     * @param string[] $words
     * @param array<array{0: RelationPointerType, 1: int, 2: SyntacticCategory, 3: int|null, 4: int|null}> $pointers
     * @dataProvider dpTestParse
     */
    public function testParse(
        string $data,
        int $offset,
        SynsetType $synsetType,
        array $words,
        array $pointers,
        string $gloss,
    ): void {
        $parser = new SynsetParser(
            tokenizerFactory: new TokenizerFactory(),
            synsetFactory: new SynsetFactory(),
            synsetTypeMapper: new SynsetTypeMapper(),
            wordFactory: new WordFactory(),
            relationPointerFactory: new RelationPointerFactory(),
            relationPointerTypeMapper: new RelationPointerTypeMapper(),
            syntacticCategoryMapper: new SyntacticCategoryMapper(),
        );
        $synset = $parser->parseSynset(new StringStream($data));

        self::assertSame($offset, $synset->getOffset());
        self::assertSame($synsetType, $synset->getType());
        self::assertSame($words, \array_map(static fn (Word $w) => $w->getValue(), $synset->getWords()));

        self::assertCount(\count($pointers), $synset->getRelationPointers());
        foreach ($synset->getRelationPointers() as $i => $pointer) {
            self::assertSame($pointers[$i][0], $pointer->getType());
            self::assertSame($pointers[$i][1], $pointer->getSynsetOffset());
            self::assertSame($pointers[$i][2], $pointer->getSynsetSyntacticCategory());
            self::assertSame($pointers[$i][3], $pointer->getSourceSynsetWordIndex());
            self::assertSame($pointers[$i][4], $pointer->getTargetSynsetWordIndex());
        }

        self::assertSame($gloss, $synset->getGloss());
    }

    /**
     * @return Iterator<mixed>
     */
    public static function dpTestParse(): Iterator
    {
        // Noun
        yield [
            '00548491 04 n 01 whistling 0 002 @ 00544270 n 0000 + 01045313 v 0102 | the act of whistling a tune; "his cheerful whistling indicated that he enjoyed his work"',
            548491,
            SynsetType::NOUN,
            ['whistling'],
            [
                [RelationPointerType::HYPERNYM, 544270, SyntacticCategory::NOUN, null, null],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 1045313, SyntacticCategory::VERB, 0, 1],
            ],
            'the act of whistling a tune; "his cheerful whistling indicated that he enjoyed his work"',
        ];
        // Verb
        yield [
            '00076153 29 v 13 vomit 0 vomit_up 0 purge 4 cast 0 sick 0 cat a be_sick 1 disgorge 0 regorge 0 retch 0 puke 0 barf 0 spew 0 spue 0 chuck 0 upchuck 0 honk 0 regurgitate 0 throw_up 0 014 @ 00072742 v 0000 + 00119553 n 1204 + 10779370 n 0d02 + 14880143 n 0b03 + 00119553 n 0b06 + 00227818 n 0a02 + 00119553 n 0805 + 07967004 n 0501 + 00119553 n 0101 + 03288430 n 0102 + 14880143 n 0101 + 10779370 n 0101 + 00119553 n 0102 ! 00077122 v 0101 05 + 02 00 + 08 13 + 08 12 + 08 0a + 08 02 | eject the contents of the stomach through the mouth; "After drinking too much, the students vomited"; "He purged continuously"; "The patient regurgitated the food we gave him last night"',
            76153,
            SynsetType::VERB,
            ['vomit', 'vomit_up', 'purge', 'cast', 'sick', 'cat', 'be_sick', 'disgorge', 'regorge', 'retch', 'puke', 'barf', 'spew', 'spue', 'chuck', 'upchuck', 'honk', 'regurgitate', 'throw_up'],
            [
                [RelationPointerType::HYPERNYM, 72742, SyntacticCategory::VERB, null, null],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 119553, SyntacticCategory::NOUN, 17, 3],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 10779370, SyntacticCategory::NOUN, 12, 1],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 14880143, SyntacticCategory::NOUN, 10, 2],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 119553, SyntacticCategory::NOUN, 10, 5],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 227818, SyntacticCategory::NOUN, 9, 1],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 119553, SyntacticCategory::NOUN, 7, 4],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 7967004, SyntacticCategory::NOUN, 4, 0],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 119553, SyntacticCategory::NOUN, 0, 0],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 3288430, SyntacticCategory::NOUN, 0, 1],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 14880143, SyntacticCategory::NOUN, 0, 0],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 10779370, SyntacticCategory::NOUN, 0, 0],
                [RelationPointerType::DERIVATIONALLY_RELATED_FORM, 119553, SyntacticCategory::NOUN, 0, 1],
                [RelationPointerType::ANTONYM, 77122, SyntacticCategory::VERB, 0, 0],
            ],
            'eject the contents of the stomach through the mouth; "After drinking too much, the students vomited"; "He purged continuously"; "The patient regurgitated the food we gave him last night"',
        ];
    }
}
