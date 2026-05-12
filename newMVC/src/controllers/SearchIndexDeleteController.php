

<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../lib/meilisearch.php';

$client = \MeilisearchConnection::getClient();

$client->deleteIndex('search');
