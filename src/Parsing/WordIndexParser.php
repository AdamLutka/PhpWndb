<?php
declare(strict_types=1);

namespace AL\PhpWndb\Parsing;

use AL\PhpWndb\Parsing\ParsedData\ParsedWordIndex;
use AL\PhpWndb\Parsing\ParsedData\ParsedWordIndexInterface;
use AL\PhpWndb\Parsing\Exceptions\WordIndexParseException;
use InvalidArgumentException;

class WordIndexParser implements WordIndexParserInterface
{
	/** @var ParsedWordIndex */
	protected $parsedWordIndex;

	/** @var TokensQueue */
	protected $tokensQueue;


	public function parseWordIndex(string $wordIndex): ParsedWordIndexInterface
	{
		try {
			$this->initParsing($wordIndex);
			$this->parseProperties();
			$this->parsePointers();
			$this->parseSynsetOffsets();
			$this->checkParseSuccess();

			return $this->parsedWordIndex;
		}
		catch (\Throwable $e) {
			throw new WordIndexParseException($wordIndex, '', 0, $e);
		}
	}


	/**
	 * @throws InvalidArgumentException
	 */
	protected function initParsing(string $wordIndex): void
	{
		$tokens = explode(' ', rtrim($wordIndex));
		if (count($tokens) < 4) {
			throw new InvalidArgumentException('There are too few tokens.');
		}

		$this->tokensQueue = new TokensQueue($tokens);
		$this->parsedWordIndex = new ParsedWordIndex();
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function parseProperties(): void
	{
		$this->parsedWordIndex->setLemma($this->tokensQueue->takeOutString());
		$this->parsedWordIndex->setPartOfSpeech($this->tokensQueue->takeOutString());
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function parsePointers(): void
	{
		$this->tokensQueue->takeOutString(); // take out obsolete token

		$pointersCount = $this->tokensQueue->takeOutDecInt();
		for ($i = 0; $i < $pointersCount; ++$i) {
			$this->parsedWordIndex->addPointerType($this->tokensQueue->takeOutString());
		}
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function parseSynsetOffsets(): void
	{
		$synsetOffsetsCount = $this->tokensQueue->takeOutDecInt();
		$this->tokensQueue->takeOutString(); // take out tagsense_cnt

		for ($i = 0; $i < $synsetOffsetsCount; ++$i) {
			$this->parsedWordIndex->addSynsetOffset($this->tokensQueue->takeOutDecInt());
		}
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function checkParseSuccess(): void
	{
		if ($this->tokensQueue->getCount() > 0) {
			throw new InvalidArgumentException('There are unexpected tokens: ' . implode(' ', $this->tokensQueue->toArray()));
		}
	}
}
