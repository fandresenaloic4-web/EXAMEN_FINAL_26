<?php

use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;
use app\controllers\UtilisateurController;


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

// Page d'accueil (login)
Flight::route('/', function () {
    Flight::render('pages/index');
});

Flight::route('/index', function () {
    $user = Flight::request()->query->l ?? null;
    $_SESSION['l']= $user;
    Flight::render('pages/login');
});


// Login GET et POST
Flight::route('GET /login', [UtilisateurController::class, 'login']);
Flight::route('POST /login', [UtilisateurController::class, 'login']);

// Logout
Flight::route('GET /logout', function () {
    session_start();
    session_destroy();
    Flight::redirect('/l');
});

Flight::route('GET /home', function () {
    Flight::render('pages/accueil');
});

Flight::route('GET /accueil', function () {
    Flight::redirect('/home');
});
    

	
