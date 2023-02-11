<?php

declare(strict_types=1);

namespace AL\PhpWndb\Parser;

use AL\PhpWndb\Model\Index\SyntacticCategory;
use AL\PhpWndb\Model\RelationPointerType;
use AL\PhpWndb\Parser\Exception\ParseException;

class RelationPointerTypeMapper
{
    /**
     * @throws ParseException
     */
    public function mapRelationPointerType(
        string $relationPointerType,
        SyntacticCategory $syntacticCategory,
    ): RelationPointerType {
        return match ($relationPointerType) {
            '!' => RelationPointerType::ANTONYM,
            '@' => RelationPointerType::HYPERNYM,
            '@i' => RelationPointerType::INSTANCE_HYPERNYM,
            '~' => RelationPointerType::HYPONYM,
            '~i' => RelationPointerType::INSTANCE_HYPONYM,
            '#m' => RelationPointerType::MEMBER_HOLONYM,
            '#s' => RelationPointerType::SUBSTANCE_HOLONYM,
            '#p' => RelationPointerType::PART_HOLONYM,
            '%m' => RelationPointerType::MEMBER_MERONYM,
            '%s' => RelationPointerType::SUBSTANCE_MERONYM,
            '%p' => RelationPointerType::PART_MERONYM,
            '=' => RelationPointerType::ATTRIBUTE,
            '+' => RelationPointerType::DERIVATIONALLY_RELATED_FORM,
            ';c' => RelationPointerType::DOMAIN_OF_SYNSET_TOPIC,
            '-c' => RelationPointerType::MEMBER_OF_THIS_DOMAIN_TOPIC,
            ';r' => RelationPointerType::DOMAIN_OF_SYNSET_REGION,
            '-r' => RelationPointerType::MEMBER_OF_THIS_DOMAIN_REGION,
            ';u' => RelationPointerType::DOMAIN_OF_SYNSET_USAGE,
            '-u' => RelationPointerType::MEMBER_OF_THIS_DOMAIN_USAGE,
            '*' => RelationPointerType::ENTAILMENT,
            '>' => RelationPointerType::CAUSE,
            '^' => RelationPointerType::ALSO_SEE,
            '$' => RelationPointerType::VERB_GROUP,
            '&' => RelationPointerType::SIMILAR_TO,
            '<' => RelationPointerType::PARTICIPLE_OF_VERB,
            ';' => RelationPointerType::DOMAIN_OF_SYNSET,
            '-' => RelationPointerType::MEMBER_OF_THIS_DOMAIN,
			'\\' => $syntacticCategory === SyntacticCategory::ADJECTIVE
                ? RelationPointerType::PERTAINYM
                : RelationPointerType::DERIVED_FROM_ADJECTIVE,
            default => throw new ParseException("Unknown relation pointer type `{$relationPointerType}`."),
        };
    }
}
