<?php
declare(strict_types=1);

namespace AL\PhpWndb\Tests\DataMapping;

use AL\PhpWndb\DataMapping\PartOfSpeechMapper;
use AL\PhpWndb\PartOfSpeechEnum;
use AL\PhpWndb\Tests\BaseTestAbstract;
use UnexpectedValueException;

class PartOfSpeechMapperTest extends BaseTestAbstract
{
	/**
	 * @dataProvider dpTestTokenToPartOfSpeech
	 */
	public function testTokenToPartOfSpeech(PartOfSpeechEnum $expectedPartOfSpeech, string $token): void
	{
		$mapper = new PartOfSpeechMapper();

		static::assertEnum($expectedPartOfSpeech, $mapper->tokenToPartOfSpeech($token));
	}

	/**
	 * @return array<array<mixed>>
	 */
	public function dpTestTokenToPartOfSpeech(): array
	{
		return [
			[PartOfSpeechEnum::NOUN(),      'n'],
			[PartOfSpeechEnum::VERB(),      'v'],
			[PartOfSpeechEnum::ADJECTIVE(), 'a'],
			[PartOfSpeechEnum::ADJECTIVE(), 's'],
			[PartOfSpeechEnum::ADVERB(),    'r'],
		];
	}

	public function testTokenToPartOfSpeechUnknown(): void
	{
		$this->expectException(UnexpectedValueException::class);

		$mapper = new PartOfSpeechMapper();
		$mapper->tokenToPartOfSpeech('not_exist');
	}
}
