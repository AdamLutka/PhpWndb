<?php
declare(strict_types=1);

namespace AL\PhpWndb\DataStorage;

use AL\PhpWndb\Exceptions\InvalidStateException;
use InvalidArgumentException;

class FileBinarySearcher implements FileBinarySearcherInterface
{
	/** @var FileReaderInterface */
	protected $fileReader;
	
	/** @var string */
	protected $recordSeparator;

	/** @var string */
	protected $recordPrefixSeparator;

	/** @var int */
	protected $readBlockSize;


	public function __construct(FileReaderInterface $fileReader, string $recordSeparator, string $recordPrefixSeparator, int $readBlockSize)
	{
		$this->fileReader = $fileReader;
		$this->recordSeparator = $recordSeparator;
		$this->recordPrefixSeparator = $recordPrefixSeparator;
		$this->readBlockSize = $readBlockSize;
	}


	public function searchFor(string $recordPrefix): ?string
	{
		if (empty($recordPrefix)) {
			throw new InvalidArgumentException('Record prefix has to be non empty.');
		}

		$startOffset = $nextStartOffset = 0;
		$endOffset = $nextEndOffset = $this->fileReader->getFileSize();

		do {
			$startOffset = $nextStartOffset;
			$endOffset = $nextEndOffset;
			$block = $this->readCenterBlock($startOffset, $endOffset);

			if ($block->isPredecessor($recordPrefix, $this->recordPrefixSeparator)) {
				$nextEndOffset = $block->getStartOffset();
			}
			else {
				$record = $block->findRecord($recordPrefix, $this->recordSeparator, $this->recordPrefixSeparator);
				if ($record !== null) {
					return $record;
				}

				$nextStartOffset = $block->getEndOffset();
			}
		}
		while ($endOffset - $startOffset > $this->readBlockSize);

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
		$blockOffset = (int)floor($startOffset + $size / 2 - $this->readBlockSize / 2);
		$block = $this->fileReader->readBlock(max(0, $blockOffset), $this->readBlockSize);

		$firstSeparatorIndex = strpos($block, $this->recordSeparator);
		$lastSeparatorIndex = strrpos($block, $this->recordSeparator);
		if ($firstSeparatorIndex === false || $lastSeparatorIndex === false || $firstSeparatorIndex === $lastSeparatorIndex) {
			throw new InvalidStateException("There is too big record around $blockOffset offset.");
		}

		// remove all before first and after last record separator
		// because they may not be complete records
		++$firstSeparatorIndex;
		$content = substr($block, $firstSeparatorIndex, $lastSeparatorIndex - $firstSeparatorIndex);

		return new Block($blockOffset + $firstSeparatorIndex, $content);
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


	public function findRecord(string $recordPrefix, string $recordSeparator, string $recordPrefixSeparator): ?string
	{
		// assumes that data doesn't start at first line of file
		$startIndex = strpos($recordSeparator . $this->content, $recordSeparator . $recordPrefix . $recordPrefixSeparator);
		if ($startIndex === false) {
			return null;
		}

		$endIndex = strpos($this->content, $recordSeparator, $startIndex);

		return $endIndex === false ? substr($this->content, $startIndex) : substr($this->content, $startIndex, $endIndex - $startIndex);
	}

	public function isPredecessor(string $recordPrefix, string $recordPrefixSeparator): bool
	{
		return strncmp($recordPrefix . $recordPrefixSeparator, $this->content, strlen($recordPrefix) + strlen($recordPrefixSeparator)) < 0;
	}
}
