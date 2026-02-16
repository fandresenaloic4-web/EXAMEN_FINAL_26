<?php

namespace app\controllers;

use Flight;
use app\models\Besoin;
use app\models\Don;
use app\models\Region;

class DashboardController {
    private $besoinModel;
    private $donModel;
    private $regionModel;

    public function __construct() {
        $this->besoinModel = new Besoin();
        $this->donModel = new Don();
        $this->regionModel = new Region();
    }

    public function index() {
        $regionId = Flight::request()->query->region_id ?? null;
        $regionId = $regionId ? (int) $regionId : null;

        $dashboardData = $this->besoinModel->getDashboardData();
        $besoinStats = $this->besoinModel->getStats();
        $donStats = $this->donModel->getStats();
        $regions = $this->regionModel->getAll();

        // Regrouper par ville
        $villes = [];
        foreach ($dashboardData as $row) {
            // Filtrer par région si sélectionnée
            if ($regionId && $row['region_id'] != $regionId) {
                continue;
            }

            $villeId = $row['ville_id'];
            if (!isset($villes[$villeId])) {
                $villes[$villeId] = [
                    'id' => $villeId,
                    'nom' => $row['ville_nom'],
                    'region' => $row['region_nom'],
                    'besoins' => []
                ];
            }
            if ($row['besoin_id']) {
                $villes[$villeId]['besoins'][] = $row;
            }
        }

        // Trier les villes par nombre de besoins décroissant
        uasort($villes, function ($a, $b) {
            return count($b['besoins']) - count($a['besoins']);
        });

        Flight::render('pages/dashboard', [
            'villes' => $villes,
            'besoinStats' => $besoinStats,
            'donStats' => $donStats,
            'regions' => $regions,
            'selectedRegion' => $regionId
        ]);
    }
}
