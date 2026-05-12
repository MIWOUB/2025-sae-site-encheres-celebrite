

<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/config.php';

$client = \MeilisearchConnection::getClient();

$client->deleteIndex('search');
