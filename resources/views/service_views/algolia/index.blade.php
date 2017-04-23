<?php
require __DIR__ . '/vendor/autoload.php';


$client = new \AlgoliaSearch\Client("0XJ54T0HH1", "e13a7a2e7399cfd925871e6d6daf5fb4");

$index = $client->initIndex('names');
$index->addObjects(
    [
        [
            'firstname' => 'Jimmie',
            'lastname'  => 'Barninger'
        ],
        [
            'firstname' => 'Warren',
            'lastname'  => 'myID1'
        ]
    ]
);
var_dump($index->search('jimmie'));

