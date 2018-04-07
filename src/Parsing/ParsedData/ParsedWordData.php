<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\ParsedData;

class ParsedWordData implements ParsedWordDataInterface
{
	/** @var string|null */
	public $value;

	/** @var int|null */
	public $lexId;


	public function getValue(): ?string
	{
		return $this->value;
	}

	public function getLexId(): ?int
	{
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