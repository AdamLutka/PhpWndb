<?php
declare(strict_types=1);

namespace AL\PhpWndb\Model\Synsets;

use AL\PhpWndb\Model\Words\WordInterface;
use InvalidArgumentException;

abstract class SynsetAbstract implements SynsetInterface
{
	/** @var int */
	protected $synsetOffset;

	/** @var string */
	protected $gloss;

	/** @var WordInterface[] */
	protected $words;


	/**
	 * @param WordInterface[] $words
	 * @throws InvalidArgumentException
	 */
	public function __construct(int $synsetOffset, string $gloss, array $words)
	{
		$this->synsetOffset = $synsetOffset;
		$this->gloss = $gloss;
		$this->words = $words;
	}


	public function getSynsetOffset(): int
	{
		return $this->synsetOffset;
	}

	public function getGloss(): string
	{
		return $this->gloss;
	}

	public function getWords(): array
	{
		return $this->words;
	}
}
