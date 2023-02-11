<?php

declare(strict_types=1);

use AL\PhpWndb\WordNetProvider;
use AL\PhpWndb\Model\RelationPointerType;

require_once(__DIR__ . '/../vendor/autoload.php');

$searchTerm = $argv[1] ?? null;
if ($searchTerm === null) {
    echo "Search term argument is missing.\n";
    exit(1);
}

$wordNet = (new WordNetProvider(cacheDir: \sys_get_temp_dir(), isDebug: true))->getWordNet();

$synsets = $wordNet->search($searchTerm);

foreach ($synsets as $synset) {
    echo $synset->getType()->name . ': ' . $synset->getGloss() . "\n";

    foreach ($synset as $word) {
        echo " - {$word->toString()}";

        foreach ($word->moveTo(RelationPointerType::ANTONYM) as $antonym) {
            echo " x {$antonym->toString()}";
        }

        echo "\n";
    }
}

if (\count($synsets) === 0) {
    echo "None synsets found.\n";
}
