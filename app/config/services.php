<?php

use flight\Engine;
use flight\database\PdoWrapper;

/**********************************************
 *           Database Service Setup           *
 **********************************************/
// MySQL Example:
$dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['dbname'] . ';charset=utf8mb4';

// Register Flight::db() service
$app->register('db', PdoWrapper::class, [ $dsn, $config['database']['user'] ?? null, $config['database']['password'] ?? null ]);

/**********************************************
 *         Third-Party Integrations           *
 **********************************************/
// Add more service registrations below as needed
