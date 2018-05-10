<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

use AL\PhpWndb\Exceptions\InvalidStateException;

class ParsedWordData implements ParsedWordDataInterface
{
	/** @var string|null */
	public $value;

	/** @var int|null */
	public $lexId;


	public function getValue(): string
	{
		if ($this->value === null) {
			throw new InvalidStateException('Value is not set.');
		}

		return $this->value;
	}

	public function getLexId(): int
	{
		if ($this->lexId === null) {
			throw new InvalidStateException('Lex id is not set.');
		}

		return $this->lexId;
	}


	public function setValue(string $value): void
	{
		$this->value = $value;
	}

	public function setLexId(int $lexId): void
	{
		$this->lexId = $lexId;
	}
}
