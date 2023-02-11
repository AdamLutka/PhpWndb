<?php

declare(strict_types=1);

namespace AL\PhpWndb\Model;

enum RelationPointerType
{
    case ANTONYM;
    case HYPERNYM;
    case INSTANCE_HYPERNYM;
    case HYPONYM;
    case INSTANCE_HYPONYM;
    case MEMBER_HOLONYM;
    case SUBSTANCE_HOLONYM;
    case PART_HOLONYM;
    case MEMBER_MERONYM;
    case SUBSTANCE_MERONYM;
    case PART_MERONYM;
    case ATTRIBUTE;
    case DERIVATIONALLY_RELATED_FORM;
    case DOMAIN_OF_SYNSET_TOPIC;
    case MEMBER_OF_THIS_DOMAIN_TOPIC;
    case DOMAIN_OF_SYNSET_REGION;
    case MEMBER_OF_THIS_DOMAIN_REGION;
    case DOMAIN_OF_SYNSET_USAGE;
    case MEMBER_OF_THIS_DOMAIN_USAGE;
    case ENTAILMENT;
    case CAUSE;
    case ALSO_SEE;
    case VERB_GROUP;
    case SIMILAR_TO;
    case PARTICIPLE_OF_VERB;
    case DOMAIN_OF_SYNSET;
    case MEMBER_OF_THIS_DOMAIN;
    case PERTAINYM;
    case DERIVED_FROM_ADJECTIVE;
}
