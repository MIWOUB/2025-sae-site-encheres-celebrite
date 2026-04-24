<?php

require __DIR__ . '/../../vendor/autoload.php';

use Meilisearch\Client;

$client = new Client('http://meilisearch:7700', 'CLE_TEST_SAE_SITE');
$index = $client->index('products');

/* Champs recherchables */
$index->updateSearchableAttributes([
    'title',
    'description',
    'category_name'
]);

/* Champs filtrables (optionnel mais propre) */
$index->updateFilterableAttributes([
    'category_id'
]);

echo "Index configuré";
