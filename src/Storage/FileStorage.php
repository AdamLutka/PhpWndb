<?php

declare(strict_types=1);

namespace AL\PhpWndb\Storage;

use AL\PhpWndb\Model\Index\SyntacticCategory;

class FileStorage implements Storage
{
    protected readonly string $filesDir;

    public function __construct(
        string $filesDir,
    ) {
        $this->filesDir = \rtrim($filesDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public function openDataStream(SyntacticCategory $syntacticCategory): Stream
    {
        return $this->openFileStream($syntacticCategory, 'data');
    }

    public function openIndexStream(SyntacticCategory $syntacticCategory): Stream
    {
        return $this->openFileStream($syntacticCategory, 'index');
    }

    protected function openFileStream(SyntacticCategory $syntacticCategory, string $fileName): Stream
    {
        $filePath = $this->filesDir . "{$fileName}." . $this->syntacticCategoryToFileExtension($syntacticCategory);
        return FileStream::open($filePath);
    }

    protected function syntacticCategoryToFileExtension(SyntacticCategory $syntacticCategory): string
    {
        return match ($syntacticCategory) {
            SyntacticCategory::ADJECTIVE => 'adj',
            SyntacticCategory::ADVERB => 'adv',
            SyntacticCategory::NOUN => 'noun',
            SyntacticCategory::VERB => 'verb',
        };
    }
}
