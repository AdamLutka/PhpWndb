<?php
declare(strict_types = 1);

namespace AL\PhpWndb\Parsing;

use AL\PhpWndb\Parsing\Exceptions\SynsetDataParseException;
use AL\PhpWndb\Parsing\ParsedData\ParsedFrameData;
use AL\PhpWndb\Parsing\ParsedData\ParsedPointerData;
use AL\PhpWndb\Parsing\ParsedData\ParsedSynsetData;
use AL\PhpWndb\Parsing\ParsedData\ParsedSynsetDataInterface;
use AL\PhpWndb\Parsing\ParsedData\ParsedWordData;
use InvalidArgumentException;

class SynsetDataParser implements SynsetDataParserInterface
{
	/** @var ParsedSynsetData */
	protected $parsedData;

	/** @var string[] */
	protected $tokens = [];


	public function parseSynsetData(string $synsetData): ParsedSynsetDataInterface
	{
		try {
			$this->initParsing($synsetData);
			$this->parseProperties();
			$this->parseWords();
			$this->parsePointers();
			$this->parseFrames();
			$this->checkParseSuccess();

			return $this->parsedData;
		}
		catch (InvalidArgumentException $e) {
			throw new SynsetDataParseException($synsetData, '', 0, $e);
		}
	}


	/**
	 * @throws InvalidArgumentException
	 */
	protected function initParsing(string $synsetData): void
	{
		list($data, $gloss) = explode('|', $synsetData) + [null, null];
		if ($gloss === null) {
			throw new InvalidArgumentException('Gloss is missing.');
		}

		$this->tokens = explode(' ', trim($data));
		$this->parsedData = new ParsedSynsetData();
		$this->parsedData->setGloss(trim($gloss));
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function parseProperties(): void
	{
		$this->parsedData->setSynsetOffset($this->transformDecInt($this->takeOutToken()));
		$this->parsedData->setLexFileNumber($this->transformDecInt($this->takeOutToken()));
		$this->parsedData->setPartOfSpeech($this->takeOutToken());
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function parseWords(): void
	{
		$wordsCount = $this->transformHexInt($this->takeOutToken());

		for ($i = 0; $i < $wordsCount; ++$i) {
			$word = new ParsedWordData();
			$word->setValue($this->transformWord($this->takeOutToken()));
			$word->setLexId($this->transformHexInt($this->takeOutToken()));

			$this->parsedData->addWord($word);
		}
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function parsePointers(): void
	{
		$pointersCount = $this->transformDecInt($this->takeOutToken());
		
		for ($i = 0; $i < $pointersCount; ++$i) {
			$pointer = new ParsedPointerData();
			$pointer->setPointerType($this->takeOutToken());
			$pointer->setSynsetOffset($this->transformDecInt($this->takeOutToken()));
			$pointer->setPartOfSpeech($this->takeOutToken());

			list($sourceIndex, $targetIndex) = $this->transformWordIndexes($this->takeOutToken());

			$pointer->setSourceWordIndex($sourceIndex);
			$pointer->setTargetWordIndex($targetIndex);

			$this->parsedData->addPointer($pointer);
		}
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function parseFrames(): void
	{
		if ($this->parsedData->getPartOfSpeech() !== 'v') {
			return;
		}

		$framesCount = $this->transformDecInt($this->takeOutToken());

		for ($i = 0; $i < $framesCount; ++$i) {
			$token = $this->takeOutToken();
			if ($token !== '+') {
				throw new InvalidArgumentException("Invalid token (+ expected): $token");
			}

			$frame = new ParsedFrameData();
			$frame->setFrameNumber($this->transformDecInt($this->takeOutToken()));
			$frame->setWordIndex($this->transformHexInt($this->takeOutToken()));

			$this->parsedData->addFrame($frame);
		}
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function checkParseSuccess(): void
	{
		if (!empty($this->tokens)) {
			throw new InvalidArgumentException('There are unexpected tokens: ' . implode(' ', $this->tokens));
		}
	}


	protected function transformWord(string $word): string
	{
		return str_replace('_', ' ', $word);
	}

	/**
	 * @return int[]
	 * @throws InvalidArgumentException
	 */
	protected function transformWordIndexes(string $wordIndexes): array
	{
		if (strlen($wordIndexes) !== 4) {
			throw new InvalidArgumentException("Invalid pointer source/target: $wordIndexes");
		}

		return [
			$this->transformHexInt(substr($wordIndexes, 0, 2)),
			$this->transformHexInt(substr($wordIndexes, 2, 2)),
		];
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function transformDecInt(string $number): int
	{
		if (!ctype_digit($number)) {
			throw new InvalidArgumentException("Decimal integer expected: $number");
		}

		return (int)$number;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function transformHexInt(string $number): int
	{
		if (!ctype_xdigit($number)) {
			throw new InvalidArgumentException("Decimal integer expected: $number");
		}

		return hexdec($number);
	}


	/**
	 * @throws InvalidArgumentException
	 */
	protected function takeOutToken(): string
	{
		if (empty($this->tokens)) {
			throw new InvalidArgumentException('No tokens left.');
		}

		return array_shift($this->tokens);
	}
}
