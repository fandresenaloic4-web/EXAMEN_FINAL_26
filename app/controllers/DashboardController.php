<?php

namespace app\controllers;

use Flight;
use app\models\Besoin;
use app\models\Don;

class DashboardController {
    private $besoinModel;
    private $donModel;

    public function __construct() {
        $this->besoinModel = new Besoin();
        $this->donModel = new Don();
    }

    public function index() {
        $dashboardData = $this->besoinModel->getDashboardData();
        $besoinStats = $this->besoinModel->getStats();
        $donStats = $this->donModel->getStats();

        // Regrouper par ville
        $villes = [];
        foreach ($dashboardData as $row) {
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

        Flight::render('pages/dashboard', [
            'villes' => $villes,
            'besoinStats' => $besoinStats,
            'donStats' => $donStats
        ]);
    }
}
