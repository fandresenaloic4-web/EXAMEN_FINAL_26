<?php

use flight\Engine;
use flight\database\PdoWrapper;

/**********************************************
 *           Database Service Setup           *
 **********************************************/
// Support both MySQL and SQLite based on config
if (!empty($config['database']['file_path'])) {
	// SQLite
	$file = $config['database']['file_path'];
	$dsn = 'sqlite:' . $file;

	// Register Flight::db() service for SQLite (no username/password)
	$app->register('db', PdoWrapper::class, [ $dsn, null, null, null ]);
} else {
	// MySQL (default)
	$dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['dbname'] . ';charset=utf8mb4';

	// Register Flight::db() service
	$app->register('db', PdoWrapper::class, [ $dsn, $config['database']['user'] ?? null, $config['database']['password'] ?? null ]);
}

/**********************************************
 *         Third-Party Integrations           *
 **********************************************/
// Add more service registrations below as needed
