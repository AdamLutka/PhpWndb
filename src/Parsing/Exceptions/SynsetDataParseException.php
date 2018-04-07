<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\Exceptions;

class SynsetDataParseException extends \RuntimeException
{
	/** @var string */
	private $synsetData;

	
	public function __construct(string $synsetData, string $message = "", int $code = 0, \Throwable $previous = null)
	{
		$msg = $message ?: "Synset data parse error - invalid syntax (see WordNet docs): $synsetData";
		parent::__construct($msg, $code, $previous);
		$this->synsetData = $synsetData;
	}


	public function getSynsetData(): string
	{
		return $this->synsetData;
	}
}