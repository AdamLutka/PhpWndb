<?php
declare(strict_types = 1);

namespace AL\PhpWndb\Exceptions;

class UnknownSynsetOffsetException extends \UnexpectedValueException
{
	/** @var int */
	private $synsetOffset;


	public function __construct(
		int $synsetOffset,
		string $message = '',
		int $code = 0,
		\Throwable $previous = null
	) {
		parent::__construct(
			$message ?: "Unknown synset offset: $synsetOffset",
			$code,
			$previous
		);
		$this->synsetOffset = $synsetOffset;
	}


	public function getSynsetOffset(): int
	{
		return $this->synsetOffset;
	}
}
