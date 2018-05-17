<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\InvalidStateException;
use InvalidArgumentException;

class TimeConsumingWordIndexLoader implements WordIndexLoaderInterface
{
	/** @internal */
	const BLOCK_SIZE = 256 * 1024; // 256 KiB


	/** @var FileReaderInterface */
	protected $fileReader;


	public function __construct(FileReaderInterface $fileReader)
	{
		$this->fileReader = $fileReader;
	}


	public function findLemmaIndexData(string $lemmaToken): ?string
	{
		if (empty($lemmaToken)) {
			throw new InvalidArgumentException('Lemma token has to be non empty.');
		}

		$startOffset = 0;
		$endOffset = $this->fileReader->getFileSize();

		for (;;) {
			$block = $this->readCenterBlock($startOffset, $endOffset);

			if ($endOffset - $startOffset <= static::BLOCK_SIZE) {
				return $block->findLemmaIndexData($lemmaToken);
			}
			elseif ($block->isPredecessor($lemmaToken)) {
				$endOffset = $block->getStartOffset();
			}
			else {
				$indexData = $block->findLemmaIndexData($lemmaToken);
				if ($indexData !== null) {
					return $indexData;
				}

				$startOffset = $block->getEndOffset();
			}
		}

		return null;
	}


	/**
	 * @throws InvalidArgumentException
	 * @throws InvalidStateException
	 */
	protected function readCenterBlock(int $startOffset, int $endOffset): Block
	{
		if ($startOffset > $endOffset) {
			throw new InvalidArgumentException("Start offset ($startOffset) has to be bigger than end offset ($endOffset).");
		}

		$size = $endOffset - $startOffset;
		$blockOffset = (int)floor($startOffset + $size / 2 - static::BLOCK_SIZE / 2);
		$block = $this->fileReader->readBlock(max(0, $blockOffset), static::BLOCK_SIZE);

		$firstLfIndex = strpos($block, "\n");
		$lastLfIndex = strrpos($block, "\n");
		if ($firstLfIndex === false || $lastLfIndex === false || $firstLfIndex === $lastLfIndex) {
			throw new InvalidStateException("There is too big index data block around $blockOffset.");
		}

		// remove all before first and after last line feed
		// because they aren't complete data indexes
		++$firstLfIndex;
		$content = substr($block, $firstLfIndex, $lastLfIndex - $firstLfIndex);

		return new Block($blockOffset + $firstLfIndex, $content);
	}
}



/** @internal */
class Block
{
	/** @var int */
	protected $startOffset;

	/** @var string */
	protected $content;


	public function __construct(int $startOffset, string $content)
	{
		$this->startOffset = $startOffset;
		$this->content = $content;
	}


	public function getStartOffset(): int
	{
		return $this->startOffset;
	}

	public function getEndOffset(): int
	{
		return $this->startOffset + strlen($this->content);
	}


	public function findLemmaIndexData(string $lemmaToken): ?string
	{
		// assumes that data doesn't start at first line of file
		$startIndex = strpos("\n" . $this->content, "\n" . $lemmaToken . ' ');
		if ($startIndex === false) {
			return null;
		}
		
		$endIndex = strpos($this->content, "\n", $startIndex);
		
		return $endIndex === false ? substr($this->content, $startIndex) : substr($this->content, $startIndex, $endIndex - $startIndex);
	}

	public function isPredecessor(string $lemmaToken): bool
	{
		return strncmp($lemmaToken, $this->content, strlen($lemmaToken)) < 0;
	}
}
