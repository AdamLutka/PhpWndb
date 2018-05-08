<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataMapping;

use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\PartOfSpeechEnum;
use UnexpectedValueException;

class RelationPointerTypeMapper implements RelationPointerTypeMapperInterface
{
	public function tokenToRelationPointerType(string $token, PartOfSpeechEnum $sourcePartOfSpeech): RelationPointerTypeEnum
	{
		switch ($token) {
			case  '!': return RelationPointerTypeEnum::ANTONYM();
			case  '@': return RelationPointerTypeEnum::HYPERNYM();
			case '@i': return RelationPointerTypeEnum::INSTANCE_HYPERNYM();
			case  '~': return RelationPointerTypeEnum::HYPONYM();
 			case '~i': return RelationPointerTypeEnum::INSTANCE_HYPONYM();
 			case '#m': return RelationPointerTypeEnum::MEMBER_HOLONYM();
 			case '#s': return RelationPointerTypeEnum::SUBSTANCE_HOLONYM();
 			case '#p': return RelationPointerTypeEnum::PART_HOLONYM();
 			case '%m': return RelationPointerTypeEnum::MEMBER_MERONYM();
 			case '%s': return RelationPointerTypeEnum::SUBSTANCE_MERONYM();
 			case '%p': return RelationPointerTypeEnum::PART_MERONYM();
			case  '=': return RelationPointerTypeEnum::ATTRIBUTE();
			case  '+': return RelationPointerTypeEnum::DERIVATIONALLY_RELATED_FORM();
 			case ';c': return RelationPointerTypeEnum::DOMAIN_OF_SYNSET_TOPIC();
 			case '-c': return RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_TOPIC();
 			case ';r': return RelationPointerTypeEnum::DOMAIN_OF_SYNSET_REGION();
 			case '-r': return RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_REGION();
 			case ';u': return RelationPointerTypeEnum::DOMAIN_OF_SYNSET_USAGE();
 			case '-u': return RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_USAGE();
			case  '*': return RelationPointerTypeEnum::ENTAILMENT();
			case  '>': return RelationPointerTypeEnum::CAUSE();
			case  '^': return RelationPointerTypeEnum::ALSO_SEE();
			case  '$': return RelationPointerTypeEnum::VERB_GROUP();
			case  '&': return RelationPointerTypeEnum::SIMILAR_TO();
			case  '<': return RelationPointerTypeEnum::PARTICIPLE_OF_VERB();
			case  ';': return RelationPointerTypeEnum::DOMAIN_OF_SYNSET();
			case  '-': return RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN();
			case '\\':
				return $sourcePartOfSpeech === PartOfSpeechEnum::ADJECTIVE()
					? RelationPointerTypeEnum::PERTAINYM()
					: RelationPointerTypeEnum::DERIVED_FROM_ADJECTIVE();

			default: throw new UnexpectedValueException("Unknown relation pointer type token: $token");
		}
	}
}
