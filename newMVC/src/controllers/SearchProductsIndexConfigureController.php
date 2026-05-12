<?php

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/config.php';

$client = \MeilisearchConnection::getClient();
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
