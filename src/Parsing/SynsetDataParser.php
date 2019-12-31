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

	/** @var TokensQueue */
	protected $tokensQueue;


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

		$tokens = explode(' ', trim((string)$data));
		$this->tokensQueue = new TokensQueue($tokens);
		$this->parsedData = new ParsedSynsetData();
		$this->parsedData->setGloss(trim($gloss));
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function parseProperties(): void
	{
		$this->parsedData->setSynsetOffset($this->tokensQueue->takeOutDecInt());
		$this->parsedData->setLexFileNumber($this->tokensQueue->takeOutDecInt());
		$this->parsedData->setPartOfSpeech($this->tokensQueue->takeOutString());
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function parseWords(): void
	{
		$wordsCount = $this->tokensQueue->takeOutHexInt();

		for ($i = 0; $i < $wordsCount; ++$i) {
			$word = new ParsedWordData();
			$word->setValue($this->tokensQueue->takeOutString());
			$word->setLexId($this->tokensQueue->takeOutHexInt());

			$this->parsedData->addWord($word);
		}
	}

	/**
	 * @throws InvalidArgumentException
	 */
	protected function parsePointers(): void
	{
		$pointersCount = $this->tokensQueue->takeOutDecInt();
		
		for ($i = 0; $i < $pointersCount; ++$i) {
			$pointer = new ParsedPointerData();
			$pointer->setPointerType($this->tokensQueue->takeOutString());
			$pointer->setSynsetOffset($this->tokensQueue->takeOutDecInt());
			$pointer->setPartOfSpeech($this->tokensQueue->takeOutString());

			list($sourceIndex, $targetIndex) = $this->tokensQueue->takeOutHexIntPair();

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

		$framesCount = $this->tokensQueue->takeOutDecInt();

		for ($i = 0; $i < $framesCount; ++$i) {
			$token = $this->tokensQueue->takeOutString();
			if ($token !== '+') {
				throw new InvalidArgumentException("Invalid token (+ expected): $token");
			}

			$frame = new ParsedFrameData();
			$frame->setFrameNumber($this->tokensQueue->takeOutDecInt());
			$frame->setWordIndex($this->tokensQueue->takeOutHexInt());

			$this->parsedData->addFrame($frame);
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
