<?php

declare(strict_types=1);

namespace AL\PhpWndb\Parser;

use AL\PhpWndb\Model\Index\SyntacticCategory;
use AL\PhpWndb\Parser\Exception\ParseException;

class SyntacticCategoryMapper
{
    /**
     * @throws ParseException
     */
    public function mapSyntacticCategory(string $syntacticCategory): SyntacticCategory
    {
        return match ($syntacticCategory) {
            'n' => SyntacticCategory::NOUN,
            'v' => SyntacticCategory::VERB,
            'a' => SyntacticCategory::ADJECTIVE,
            'r' => SyntacticCategory::ADVERB,
            default => throw new ParseException("Unknown syntactic category `{$syntacticCategory}`."),
        };
    }
}
