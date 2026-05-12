<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../lib/meilisearch.php';

$client = \MeilisearchConnection::getClient();
$index = $client->index('search');

$results = $index->search('f');

echo '<pre>';
print_r($results->getHits());
