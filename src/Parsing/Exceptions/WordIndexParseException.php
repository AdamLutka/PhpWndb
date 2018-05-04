<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing\Exceptions;

class WordIndexParseException extends \RuntimeException
{
	/** @var string */
	protected $wordIndex;

	
	public function __construct(string $wordIndex, string $message = "", int $code = 0, \Throwable $previous = null)
	{
		$msg = $message ?: "Word index parse error - invalid syntax (see WordNet docs): $wordIndex";
		parent::__construct($msg, $code, $previous);
		$this->wordIndex = $wordIndex;
	}


	public function getWordIndex(): string
	{
		return $this->wordIndex;
	}
}
