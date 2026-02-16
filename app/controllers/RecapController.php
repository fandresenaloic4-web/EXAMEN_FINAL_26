<?php

namespace app\controllers;

use Flight;
use app\models\Besoin;
use app\models\Don;
use app\models\Distribution;
use app\models\Achat;

class RecapController {
    private $besoinModel;
    private $donModel;
    private $distModel;
    private $achatModel;

    public function __construct() {
        $this->besoinModel = new Besoin();
        $this->donModel = new Don();
        $this->distModel = new Distribution();
        $this->achatModel = new Achat();
    }

    /**
     * Page récapitulative
     */
    public function index() {
        $data = $this->getRecapData();

        Flight::render('pages/recap', $data);
    }

    /**
     * API JSON pour le récap (Ajax)
     */
    public function apiRecap() {
        $data = $this->getRecapData();
        Flight::json($data);
    }

    /**
     * Calculer toutes les données récapitulatives
     */
    private function getRecapData() {
        // Montant total des besoins
        $besoins = $this->besoinModel->getAll();
        $montantTotal = 0;
        $montantSatisfait = 0;

        foreach ($besoins as $b) {
            $montantTotal += $b['prix_unitaire'] * $b['quantite_demandee'];
            $attribue = $b['quantite_demandee'] - $b['quantite_restante'];
            $montantSatisfait += $b['prix_unitaire'] * $attribue;
        }

        $montantRestant = $montantTotal - $montantSatisfait;
        $pourcentage = $montantTotal > 0 ? round(($montantSatisfait / $montantTotal) * 100, 1) : 0;

        // Stats des dons
        $dons = $this->donModel->getAll();
        $totalDons = count($dons);
        $totalQuantiteDons = 0;
        foreach ($dons as $d) {
            $totalQuantiteDons += $d['quantite'];
        }

        // Stats des distributions
        $distributions = $this->distModel->getAll();
        $totalDistributions = count($distributions);

        // Stats des achats
        $statsAchats = $this->achatModel->getStats();

        // Argent disponible
        $argentDisponible = $this->achatModel->getMontantArgentDisponible();

        return [
            'montantTotal' => $montantTotal,
            'montantSatisfait' => $montantSatisfait,
            'montantRestant' => $montantRestant,
            'pourcentage' => $pourcentage,
            'totalBesoins' => count($besoins),
            'totalDons' => $totalDons,
            'totalQuantiteDons' => $totalQuantiteDons,
            'totalDistributions' => $totalDistributions,
            'statsAchats' => $statsAchats,
            'argentDisponible' => $argentDisponible
        ];
    }
}
