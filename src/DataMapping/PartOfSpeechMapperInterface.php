<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataMapping;

use AL\PhpWndb\PartOfSpeechEnum;

interface PartOfSpeechMapperInterface
{
	public function tokenToPartOfSpeech(string $token): PartOfSpeechEnum;
}
