<?php

namespace app\controllers;

use Flight;
use app\models\Achat;
use app\models\Besoin;
use app\models\Ville;
use app\models\Don;

class AchatController {
    private $achatModel;
    private $besoinModel;
    private $villeModel;
    private $donModel;

    public function __construct() {
        $this->achatModel = new Achat();
        $this->besoinModel = new Besoin();
        $this->villeModel = new Ville();
        $this->donModel = new Don();
    }

    /**
     * Liste des achats avec filtre par ville
     */
    public function index() {
        $villeId = Flight::request()->query->ville_id ?: null;
        $achats = $this->achatModel->getAll($villeId);
        $stats = $this->achatModel->getStats();
        $argentDisponible = $this->achatModel->makavolaDisponible();
        $villes = $this->villeModel->getAll();

        Flight::render('pages/achats/index', [
            'achats' => $achats,
            'stats' => $stats,
            'argentDisponible' => $argentDisponible,
            'villes' => $villes,
            'villeIdFiltre' => $villeId
        ]);
    }

    /**
     * Formulaire de création d'achat
     */
    public function create() {
        $besoinId = Flight::request()->query->besoin_id ?: null;
        $error = Flight::request()->query->error ?: null;

        // Besoins éligibles: quantite_restante > 0 et catégorie != argent
        $besoins = $this->besoinModel->makaazyrehetra();
        $besoinsEligibles = array_filter($besoins, function($b) {
            return $b['quantite_restante'] > 0 && strtolower($b['categorie_nom']) !== 'argent';
        });

        $argentDisponible = $this->achatModel->makavolaDisponible();
        $fraisPourcent = Flight::get('frais_achat');

        Flight::render('pages/achats/form', [
            'besoins' => array_values($besoinsEligibles),
            'besoinIdPreselect' => $besoinId,
            'argentDisponible' => $argentDisponible,
            'fraisPourcent' => $fraisPourcent,
            'error' => $error,
            'action' => url('/achats/store')
        ]);
    }

    /**
     * Enregistrer un achat
     */
    public function store() {
        $data = Flight::request()->data;

        $besoinId = (int) $data->besoin_id;
        $quantite = (int) $data->quantite;
        $prixUnitaire = (float) $data->prix_unitaire;
        $fraisPourcent = (float) Flight::get('frais_achat');

        // Vérifier le besoin
        $besoin = $this->besoinModel->getById($besoinId);
        if (!$besoin) {
            Flight::redirect('/achats/create?error=besoin_invalide');
            return;
        }

        // Vérifier que la catégorie n'est pas "argent"
        if (strtolower($besoin['categorie_nom']) === 'argent') {
            Flight::redirect('/achats/create?error=categorie_argent');
            return;
        }

        // Vérifier la quantité
        if ($quantite <= 0 || $quantite > $besoin['quantite_restante']) {
            Flight::redirect('/achats/create?besoin_id=' . $besoinId . '&error=quantite_invalide');
            return;
        }

        // Vérifier s'il reste des dons disponibles pour cette catégorie
        $donsDisponibles = $this->donModel->getAvailableByCategorie($besoin['categorie_id']);
        $totalDonsDisponibles = 0;
        foreach ($donsDisponibles as $don) {
            $totalDonsDisponibles += (int) $don['quantite_disponible'];
        }
        if ($totalDonsDisponibles > 0) {
            Flight::redirect('/achats/create?besoin_id=' . $besoinId . '&error=dons_disponibles');
            return;
        }

        // Vérifier le montant disponible
        $montantHt = $prixUnitaire * $quantite;
        $montantTotal = $montantHt + ($montantHt * $fraisPourcent / 100);
        $argentDisponible = $this->achatModel->makavolaDisponible();

        if ($montantTotal > $argentDisponible) {
            Flight::redirect('/achats/create?besoin_id=' . $besoinId . '&error=argent_insuffisant');
            return;
        }

        // Créer l'achat
        $this->achatModel->inserer($besoinId, $quantite, $prixUnitaire, $fraisPourcent);

        // Mettre à jour quantité restante du besoin
        $this->besoinModel->updateQuantiteRestante($besoinId, $quantite);

        Flight::redirect('/achats');
    }

    /**
     * Supprimer un achat
     */
    public function delete($id) {
        $achat = $this->achatModel->getById((int) $id);

        if ($achat) {
            // Restaurer la quantité restante du besoin
            $this->besoinModel->updateQuantiteRestante($achat['besoin_id'], -$achat['quantite']);
            $this->achatModel->delete((int) $id);
        }

        Flight::redirect('/achats');
    }

    /**
     * API: Vérifier les dons disponibles pour un besoin
     */
    public function checkDonsDisponibles($besoinId) {
        $besoin = $this->besoinModel->getById((int) $besoinId);
        if (!$besoin) {
            Flight::json(['error' => 'Besoin introuvable'], 404);
            return;
        }

        $donsDisponibles = $this->donModel->getAvailableByCategorie($besoin['categorie_id']);
        $totalDisponible = 0;
        foreach ($donsDisponibles as $don) {
            $totalDisponible += (int) $don['quantite_disponible'];
        }

        Flight::json([
            'besoin' => $besoin,
            'dons_disponibles' => $totalDisponible,
            'peut_acheter' => ($totalDisponible <= 0)
        ]);
    }
}
