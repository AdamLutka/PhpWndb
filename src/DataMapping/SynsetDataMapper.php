<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataMapping;

use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\Model\Synsets\Adjectives\SynsetAdjectivesCategoryEnum;
use AL\PhpWndb\Model\Synsets\Adverbs\SynsetAdverbsCategoryEnum;
use AL\PhpWndb\Model\Synsets\Nouns\SynsetNounsCategoryEnum;
use AL\PhpWndb\Model\Synsets\Verbs\SynsetVerbsCategoryEnum;
use UnexpectedValueException;

class SynsetDataMapper implements SynsetDataMapperInterface
{
	public function mapPartOfSpeech(string $data): PartOfSpeechEnum
	{
		switch ($data) {
			case "n": return PartOfSpeechEnum::NOUN();
			case "v": return PartOfSpeechEnum::VERB();
			case "a": return PartOfSpeechEnum::ADJECTIVE();
			case "s": return PartOfSpeechEnum::ADJECTIVE_SATELLITE();
			case "r": return PartOfSpeechEnum::ADVERB();
			default: throw new UnexpectedValueException("Unknown part of speech data: $data");
		}
	}

	public function mapRelationPointerType(string $data, PartOfSpeechEnum $sourcePartOfSpeech): RelationPointerTypeEnum
	{
		switch ($data) {
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
			case '\\':
				return $sourcePartOfSpeech === PartOfSpeechEnum::ADJECTIVE()
					? RelationPointerTypeEnum::PERTAINYM()
					: RelationPointerTypeEnum::DERIVED_FROM_ADJECTIVE();

			default: throw new UnexpectedValueException("Unknown relation pointer type data: $data");
		}
	}


	public function mapSynsetAdjectivesCategory(int $data): SynsetAdjectivesCategoryEnum
	{
		switch ($data) {
			case  0: return SynsetAdjectivesCategoryEnum::ALL();
			case  1: return SynsetAdjectivesCategoryEnum::RELATIONAL();
			case 44: return SynsetAdjectivesCategoryEnum::PARTICIPIAL();
			default: throw new UnexpectedValueException("Unknown synset adjectives category data: $data");
		}
	}

	public function mapSynsetAdverbsCategory(int $data): SynsetAdverbsCategoryEnum
	{
		switch ($data) {
			case 2: return SynsetAdverbsCategoryEnum::ALL();
			default: throw new UnexpectedValueException("Unknown synset adverbs category data: $data");
		}
	}

	public function mapSynsetNounsCategory(int $data): SynsetNounsCategoryEnum
	{
		switch ($data) {
			case  3: return SynsetNounsCategoryEnum::TOPS();
			case  4: return SynsetNounsCategoryEnum::ACT();
			case  5: return SynsetNounsCategoryEnum::ANIMAL();
			case  6: return SynsetNounsCategoryEnum::ARTIFACT();
			case  7: return SynsetNounsCategoryEnum::ATTRIBUTE();
			case  8: return SynsetNounsCategoryEnum::BODY();
			case  9: return SynsetNounsCategoryEnum::COGNITION();
			case 10: return SynsetNounsCategoryEnum::COMMUNICATION();
			case 11: return SynsetNounsCategoryEnum::EVENT();
			case 12: return SynsetNounsCategoryEnum::FEELING();
			case 13: return SynsetNounsCategoryEnum::FOOD();
			case 14: return SynsetNounsCategoryEnum::GROUP();
			case 15: return SynsetNounsCategoryEnum::LOCATION();
			case 16: return SynsetNounsCategoryEnum::MOTIVE();
			case 17: return SynsetNounsCategoryEnum::OBJECT();
			case 18: return SynsetNounsCategoryEnum::PERSON();
			case 19: return SynsetNounsCategoryEnum::PHENOMENON();
			case 20: return SynsetNounsCategoryEnum::PLANT();
			case 21: return SynsetNounsCategoryEnum::POSSESSION();
			case 22: return SynsetNounsCategoryEnum::PROCESS();
			case 23: return SynsetNounsCategoryEnum::QUANTITY();
			case 24: return SynsetNounsCategoryEnum::RELATION();
			case 25: return SynsetNounsCategoryEnum::SHAPE();
			case 26: return SynsetNounsCategoryEnum::STATE();
			case 27: return SynsetNounsCategoryEnum::SUBSTANCE();
			case 28: return SynsetNounsCategoryEnum::TIME();
			default: throw new UnexpectedValueException("Unknown synset nouns category data: $data");
		}
	}

	public function mapSynsetVerbsCategory(int $data): SynsetVerbsCategoryEnum
	{
		switch ($data) {
			case 29: return SynsetVerbsCategoryEnum::BODY();
			case 30: return SynsetVerbsCategoryEnum::CHANGE();
			case 31: return SynsetVerbsCategoryEnum::COGNITION();
			case 32: return SynsetVerbsCategoryEnum::COMMUNICATION();
			case 33: return SynsetVerbsCategoryEnum::COMPETITION();
			case 34: return SynsetVerbsCategoryEnum::CONSUMPTION();
			case 35: return SynsetVerbsCategoryEnum::CONTACT();
			case 36: return SynsetVerbsCategoryEnum::CREATION();
			case 37: return SynsetVerbsCategoryEnum::EMOTION();
			case 38: return SynsetVerbsCategoryEnum::MOTION();
			case 39: return SynsetVerbsCategoryEnum::PERCEPTION();
			case 40: return SynsetVerbsCategoryEnum::POSSESSION();
			case 41: return SynsetVerbsCategoryEnum::SOCIAL();
			case 42: return SynsetVerbsCategoryEnum::STATIVE();
			case 43: return SynsetVerbsCategoryEnum::WEATHER();
			default: throw new UnexpectedValueException("Unknown synset verbs category data: $data");
		}
	}
}
