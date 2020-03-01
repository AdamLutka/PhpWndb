<?php
declare(strict_types=1);

use AL\PhpWndb\Examples\MyDiContainerFactory;
use AL\PhpWndb\WordNet;

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/MyDiContainerFactory.php');


$containerFactory = new MyDiContainerFactory();
$container = $containerFactory->createContainer();

/** @var WordNet */
$wordNet = $container->get(WordNet::class);

$synsets = $wordNet->searchSynsets('cat')->getAllSynsets();

foreach ($synsets as $synset) {
	echo "  " . $synset->getGloss() . "\n";
}
