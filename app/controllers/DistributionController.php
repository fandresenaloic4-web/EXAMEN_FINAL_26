<?php

namespace app\controllers;

use Flight;
use app\models\Distribution;
use app\models\Don;
use app\models\Besoin;

class DistributionController {
    private $distModel;
    private $donModel;
    private $besoinModel;

    public function __construct() {
        $this->distModel = new Distribution();
        $this->donModel = new Don();
        $this->besoinModel = new Besoin();
    }

    public function index() {
        $distributions = $this->distModel->getAll();

        Flight::render('pages/distributions/index', [
            'distributions' => $distributions
        ]);
    }

    public function create() {
        Flight::render('pages/distributions/form', [
            'dons' => $this->donModel->getAll(),
            'besoins' => $this->besoinModel->getAll(),
            'action' => url('/distributions/store')
        ]);
    }

    public function store() {
        $data = Flight::request()->data;

        $donId = (int) $data->don_id;
        $besoinId = (int) $data->besoin_id;
        $quantite = (int) $data->quantite_attribuee;
        $date = $data->date_distribution ?: date('Y-m-d');

        // Vérifier la disponibilité du don
        $don = $this->donModel->getById($donId);
        if (!$don || $don['quantite_disponible'] < $quantite) {
            Flight::redirect('/distributions/create?error=don_insuffisant');
            return;
        }

        // Vérifier le besoin restant
        $besoin = $this->besoinModel->getById($besoinId);
        if (!$besoin || $besoin['quantite_restante'] < $quantite) {
            Flight::redirect('/distributions/create?error=besoin_depasse');
            return;
        }

        // Vérifier même catégorie
        if ($don['categorie_id'] != $besoin['categorie_id']) {
            Flight::redirect('/distributions/create?error=categorie_differente');
            return;
        }

        // Créer la distribution
        $this->distModel->inserer($donId, $besoinId, $quantite, $date);

        // Mettre à jour la quantité restante du besoin
        $this->besoinModel->updateQuantiteRestante($besoinId, $quantite);

        Flight::redirect('/distributions');
    }

    public function dispatch() {
        $mode = Flight::request()->query->mode ?: 'validate';
        $strategy = Flight::request()->query->strategy ?: 'fifo'; // fifo | proportion | smallest

        if ($mode === 'simulate') {
            $log = $this->distModel->simulateDispatch($strategy);
            Flight::render('pages/distributions/dispatch_result', [
                'log' => $log,
                'mode' => 'simulated',
                'strategy' => $strategy
            ]);
        } else {
            $log = $this->distModel->dispatchDons($strategy);
            Flight::render('pages/distributions/dispatch_result', [
                'log' => $log,
                'mode' => 'validated',
                'strategy' => $strategy
            ]);
        }
    }

    public function delete($id) {
        $dist = $this->distModel->getById((int) $id);

        if ($dist) {
            // Restaurer la quantité restante du besoin
            $this->besoinModel->updateQuantiteRestante($dist['besoin_id'], -$dist['quantite_attribuee']);
            $this->distModel->delete((int) $id);
        }

        Flight::redirect('/distributions');
    }

    /**
     * API: Récupérer les besoins par catégorie (pour le formulaire dynamique)
     */
    public function getBesoinsByCategorie($categorieId) {
        $besoins = $this->besoinModel->getByCategorie((int) $categorieId);
        Flight::json($besoins);
    }

    /**
     * API: Récupérer les dons disponibles par catégorie
     */
    public function getDonsByCategorie($categorieId) {
        $dons = $this->donModel->getAvailableByCategorie((int) $categorieId);
        Flight::json($dons);
    }
}
