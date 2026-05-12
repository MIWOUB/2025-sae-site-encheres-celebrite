<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../lib/meilisearch.php';

$client = \MeilisearchConnection::getClient();


try {
    $client->createIndex('search', [
        'primaryKey' => 'id'
    ]);
} catch (Exception $e) {
}


$index = $client->index('search');

$index->updateSettings([
    'searchableAttributes' => ['title', 'category_name', 'celebrity_name'],
    'filterableAttributes' => ['type', 'category_id', 'celebrity_id'],
    'displayedAttributes' => ['id', 'type', 'title', 'product_id', 'category_id', 'celebrity_id', 'category_name', 'celebrity_name']
]);


echo "Index 'search' créé et configuré avec succès";
