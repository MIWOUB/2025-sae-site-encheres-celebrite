<?php
require_once __DIR__ . '/../lib/meilisearch.php';

define('MEILI_HOST', getenv('MEILI_HOST') ?: 'http://meilisearch:7700');
define('MEILI_KEY', getenv('MEILI_KEY') ?: '');
