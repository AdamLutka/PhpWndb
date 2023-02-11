<?php

declare(strict_types=1);

namespace AL\PhpWndb\Tests;

use AL\PhpWndb\Model\Data\SynsetType;
use AL\PhpWndb\Model\RelationPointerType;
use AL\PhpWndb\WordNetProvider;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    public function test(): void
    {
        $wordNet = (new WordNetProvider())->getWordNet();
        $synsets = $wordNet->search('happy');

        self::assertCount(4, $synsets);

        $adjSynsets = $synsets->onlyType(SynsetType::ADJECTIVE);

        self::assertCount(1, $adjSynsets);

        $synset = $adjSynsets->getFirst();

        self::assertSame(
            'enjoying or showing or marked by joy or pleasure; "a happy smile"; "spent many happy days on the beach"; "a happy marriage"',
            $synset->getGloss(),
        );
        self::assertCount(1, $synset);

        $word = $synset->getFirst();
        self::assertSame('happy', $word->toString());

        $antonyms = $word->moveTo(RelationPointerType::ANTONYM);
        self::assertCount(1, $antonyms);

        $antonym = $antonyms->getLast();
        self::assertSame('unhappy', $antonym->toString());

        self::assertCount(0, $adjSynsets->omitType(SynsetType::ADJECTIVE));
    }
}
