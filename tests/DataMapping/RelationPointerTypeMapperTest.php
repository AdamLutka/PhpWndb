<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\DataMapping;

use AL\PhpWndb\DataMapping\RelationPointerTypeMapper;
use AL\PhpWndb\Model\Relations\RelationPointerTypeEnum;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Tests\BaseTestAbstract;

class RelationPointerTypeMapperTest extends BaseTestAbstract
{
	/**
	 * @dataProvider dpTestTokenToRelationPointerType
	 */
	public function testTokenToRelationPointerType(
		RelationPointerTypeEnum $expectedRelationPointerType,
		string $token,
		PartOfSpeechEnum $sourcePartOfSpeech
	): void {
		$mapper = new RelationPointerTypeMapper();

		static::assertEnum($expectedRelationPointerType, $mapper->tokenToRelationPointerType($token, $sourcePartOfSpeech));
	}

	public function dpTestTokenToRelationPointerType(): array
	{
		return [
			[RelationPointerTypeEnum::ANTONYM(),                         '!', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::HYPERNYM(),                        '@', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::INSTANCE_HYPERNYM(),              '@i', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::HYPONYM(),                         '~', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::INSTANCE_HYPONYM(),               '~i', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::MEMBER_HOLONYM(),                 '#m', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::SUBSTANCE_HOLONYM(),              '#s', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::PART_HOLONYM(),                   '#p', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::MEMBER_MERONYM(),                 '%m', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::SUBSTANCE_MERONYM(),              '%s', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::PART_MERONYM(),                   '%p', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::ATTRIBUTE(),                       '=', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::DERIVATIONALLY_RELATED_FORM(),     '+', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::DOMAIN_OF_SYNSET_TOPIC(),         ';c', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_TOPIC(),    '-c', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::DOMAIN_OF_SYNSET_REGION(),        ';r', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_REGION(),   '-r', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::DOMAIN_OF_SYNSET_USAGE(),         ';u', PartOfSpeechEnum::NOUN()],
 			[RelationPointerTypeEnum::MEMBER_OF_THIS_DOMAIN_USAGE(),    '-u', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::ENTAILMENT(),                      '*', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::CAUSE(),                           '>', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::ALSO_SEE(),                        '^', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::VERB_GROUP(),                      '$', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::SIMILAR_TO(),                      '&', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::PARTICIPLE_OF_VERB(),              '<', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::DERIVED_FROM_ADJECTIVE(),         '\\', PartOfSpeechEnum::NOUN()],
			[RelationPointerTypeEnum::PERTAINYM(),                      '\\', PartOfSpeechEnum::ADJECTIVE()],
		];
	}

	/**
	 * @expectedException \UnexpectedValueException
	 */
	public function testTokenToRelationPointerTypeUnknown(): void
	{
		$mapper = new RelationPointerTypeMapper();
		$mapper->tokenToRelationPointerType('not_exist', PartOfSpeechEnum::NOUN());
	}
}
