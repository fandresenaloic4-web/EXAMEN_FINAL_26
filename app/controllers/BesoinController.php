<?php

namespace app\controllers;

use Flight;
use app\models\Besoin;
use app\models\Ville;
use app\models\Categorie;

class BesoinController {
    private $besoinModel;
    private $villeModel;
    private $categorieModel;

    public function __construct() {
        $this->besoinModel = new Besoin();
        $this->villeModel = new Ville();
        $this->categorieModel = new Categorie();
    }

    public function index() {
        $besoins = $this->besoinModel->getAll();

        Flight::render('pages/besoins/index', [
            'besoins' => $besoins
        ]);
    }

    public function create() {
        Flight::render('pages/besoins/form', [
            'villes' => $this->villeModel->getAll(),
            'categories' => $this->categorieModel->getAll(),
            'besoin' => null,
            'action' => url('/besoins/store')
        ]);
    }

    public function store() {
        $data = Flight::request()->data;

        $this->besoinModel->inserer(
            (int) $data->ville_id,
            (int) $data->categorie_id,
            $data->libelle,
            (float) $data->prix_unitaire,
            (int) $data->quantite_demandee
        );

        Flight::redirect('/besoins');
    }

    public function edit($id) {
        $besoin = $this->besoinModel->getById((int) $id);
        if (!$besoin) {
            Flight::redirect('/besoins');
            return;
        }

        Flight::render('pages/besoins/form', [
            'villes' => $this->villeModel->getAll(),
            'categories' => $this->categorieModel->getAll(),
            'besoin' => $besoin,
            'action' => url('/besoins/update/' . $id)
        ]);
    }

    public function update($id) {
        $data = Flight::request()->data;

        $this->besoinModel->update(
            (int) $id,
            (int) $data->ville_id,
            (int) $data->categorie_id,
            $data->libelle,
            (float) $data->prix_unitaire,
            (int) $data->quantite_demandee
        );

        Flight::redirect('/besoins');
    }

    public function delete($id) {
        $this->besoinModel->delete((int) $id);
        Flight::redirect('/besoins');
    }
}
