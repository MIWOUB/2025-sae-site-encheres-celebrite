<?php

use Meilisearch\Client;

class MeilisearchConnection
{
    private static ?Client $client = null;

    public static function getClient(): Client
    {
        if (self::$client === null) {
            $host = getenv('MEILI_HOST') ?: 'http://meilisearch:7700';
            $key = getenv('MEILI_KEY') ?: '';

            self::$client = new Client($host, $key);
        }

        return self::$client;
    }
}