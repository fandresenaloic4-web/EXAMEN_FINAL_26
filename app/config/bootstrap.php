<?php

$ds = DIRECTORY_SEPARATOR;
require(__DIR__ . $ds . '..' . $ds . '..' . $ds . 'vendor' . $ds . 'autoload.php');

// Load the Flight framework class

if(file_exists(__DIR__. $ds . 'config.php') === false) {
	Flight::halt(500, 'Config file not found. Please create a config.php file in the app/config directory to get started.');
}

$app = Flight::app();

$config = require('config.php');

// ========================================
// Détection automatique du BASE_URL
// ========================================
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseUrl = rtrim(dirname($scriptName), '/');
// Enlever /public si on route via le root index.php
$baseUrl = preg_replace('#/public$#', '', $baseUrl);

if (!defined('BASE_URL')) {
    define('BASE_URL', $baseUrl);
}

// Helper pour générer des URLs dans les vues
if (!function_exists('url')) {
    function url($path = '') {
        return BASE_URL . $path;
    }
}

// Configurer FlightPHP pour utiliser le base_url
$app->set('flight.base_url', BASE_URL);

// Enregistrer les frais d'achat (V2)
$app->set('frais_achat', $config['frais_achat'] ?? 10);

require('services.php');


$router = $app->router();


require('routes.php');


$app->start();
