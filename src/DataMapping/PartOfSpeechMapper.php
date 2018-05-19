<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataMapping;

use AL\PhpWndb\PartOfSpeechEnum;
use UnexpectedValueException;

class PartOfSpeechMapper implements PartOfSpeechMapperInterface
{
	public function tokenToPartOfSpeech(string $token): PartOfSpeechEnum
	{
		switch ($token) {
			case "n": return PartOfSpeechEnum::NOUN();
			case "v": return PartOfSpeechEnum::VERB();
			case "s": // ADJECTIVE SATELLITE
			case "a": return PartOfSpeechEnum::ADJECTIVE();
			case "r": return PartOfSpeechEnum::ADVERB();
			default: throw new UnexpectedValueException("Unknown part of speech token: $token");
		}
	}
}
