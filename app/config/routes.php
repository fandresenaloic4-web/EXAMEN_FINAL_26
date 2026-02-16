<?php

use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;
use app\controllers\DashboardController;
use app\controllers\BesoinController;
use app\controllers\DonController;
use app\controllers\DistributionController;
use app\controllers\VilleController;
use app\controllers\AchatController;
use app\controllers\RecapController;


/** 
 * @var Router $router 
 * @var Engine $app
 */

// Middleware pour parser les requêtes JSON
Flight::before('start', function() {
    $contentType = Flight::request()->header('Content-Type') ?? '';
    $method = Flight::request()->method;
    
    if (in_array($method, ['POST', 'PUT', 'PATCH']) && strpos($contentType, 'application/json') !== false) {
        $body = file_get_contents('php://input');
        if (!empty($body)) {
            $json = json_decode($body, true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }
    }
});

Flight::route('GET /test-db', function() {
    $db = Flight::db();
    echo 'DB OK';
});

// Page d'accueil -> Dashboard
Flight::route('/', function () {
    Flight::redirect('/dashboard');
});

// ========================================
// DASHBOARD
// ========================================
Flight::route('GET /dashboard', [DashboardController::class, 'index']);

// ========================================
// À PROPOS
// ========================================
Flight::route('GET /about', function () {
    Flight::render('pages/about');
});

// ========================================
// VILLES
// ========================================
Flight::route('GET /villes', [VilleController::class, 'index']);
Flight::route('GET /villes/create', [VilleController::class, 'create']);
Flight::route('POST /villes/store', [VilleController::class, 'store']);
Flight::route('GET /villes/edit/@id', [VilleController::class, 'edit']);
Flight::route('POST /villes/update/@id', [VilleController::class, 'update']);
Flight::route('GET /villes/delete/@id', [VilleController::class, 'delete']);

// ========================================
// BESOINS
// ========================================
Flight::route('GET /besoins', [BesoinController::class, 'index']);
Flight::route('GET /besoins/create', [BesoinController::class, 'create']);
Flight::route('POST /besoins/store', [BesoinController::class, 'store']);
Flight::route('GET /besoins/edit/@id', [BesoinController::class, 'edit']);
Flight::route('POST /besoins/update/@id', [BesoinController::class, 'update']);
Flight::route('GET /besoins/delete/@id', [BesoinController::class, 'delete']);

// ========================================
// DONS
// ========================================
Flight::route('GET /dons', [DonController::class, 'index']);
Flight::route('GET /dons/create', [DonController::class, 'create']);
Flight::route('POST /dons/store', [DonController::class, 'store']);
Flight::route('GET /dons/edit/@id', [DonController::class, 'edit']);
Flight::route('POST /dons/update/@id', [DonController::class, 'update']);
Flight::route('GET /dons/delete/@id', [DonController::class, 'delete']);

// ========================================
// DISTRIBUTIONS
// ========================================
Flight::route('GET /distributions', [DistributionController::class, 'index']);
Flight::route('GET /distributions/create', [DistributionController::class, 'create']);
Flight::route('POST /distributions/store', [DistributionController::class, 'store']);
Flight::route('GET /distributions/dispatch', [DistributionController::class, 'dispatch']);
Flight::route('GET /distributions/delete/@id', [DistributionController::class, 'delete']);

// ========================================
// API (pour le formulaire dynamique)
// ========================================
Flight::route('GET /api/besoins/categorie/@id', [DistributionController::class, 'getBesoinsByCategorie']);
Flight::route('GET /api/dons/categorie/@id', [DistributionController::class, 'getDonsByCategorie']);

// ========================================
// ACHATS (V2)
// ========================================
Flight::route('GET /achats', [AchatController::class, 'index']);
Flight::route('GET /achats/create', [AchatController::class, 'create']);
Flight::route('POST /achats/store', [AchatController::class, 'store']);
Flight::route('GET /achats/delete/@id', [AchatController::class, 'delete']);

// ========================================
// RÉCAPITULATION (V2)
// ========================================
Flight::route('GET /recap', [RecapController::class, 'index']);

// ========================================
// API V2
// ========================================
Flight::route('GET /api/recap', [RecapController::class, 'apiRecap']);
Flight::route('GET /api/achats/check/@besoinId', [AchatController::class, 'checkDonsDisponibles']);
