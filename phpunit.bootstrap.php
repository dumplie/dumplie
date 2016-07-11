<?php

require_once __DIR__.'/vendor/autoload.php';

define('DUMPLIE_SRC_PATH', __DIR__ . '/src');

if (!getenv('DUMPLIE_TEST_DB_CONNECTION')) {
    define('DUMPLIE_TEST_DB_CONNECTION', json_encode(['driver' => 'pdo_sqlite', 'url' => 'sqlite:///:memory:']));
} else {
    define('DUMPLIE_TEST_DB_CONNECTION', getenv('DUMPLIE_TEST_DB_CONNECTION'));
}