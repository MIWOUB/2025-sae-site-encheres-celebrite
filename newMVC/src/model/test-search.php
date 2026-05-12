<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../controllers/config.php';

$client = \MeilisearchConnection::getClient();
$index = $client->index('search');

$results = $index->search('f');

echo '<pre>';
print_r($results->getHits());
