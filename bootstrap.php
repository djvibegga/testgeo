<?php

require_once __DIR__ . '/env.php';

$connectionString = 'host=' . DB_HOST
                . ' port=' . DB_PORT
                . ' dbname=' . DB_NAME
                . ' user=' . DB_USER
                . ' password=' . DB_PASS;

$pgConnect = function() use ($connectionString) {
    return pg_connect($connectionString);
};