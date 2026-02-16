<?php

namespace app\controllers;

use Flight;
use app\models\Don;
use app\models\Categorie;

class DonController {
    private $donModel;
    private $categorieModel;

    public function __construct() {
        $this->donModel = new Don();
        $this->categorieModel = new Categorie();
    }

    public function index() {
        $dons = $this->donModel->getAll();

        Flight::render('pages/dons/index', [
            'dons' => $dons
        ]);
    }

    public function create() {
        Flight::render('pages/dons/form', [
            'categories' => $this->categorieModel->getAll(),
            'don' => null,
            'action' => '/dons/store'
        ]);
    }

    public function store() {
        $data = Flight::request()->data;

        $this->donModel->inserer(
            $data->nom,
            (int) $data->categorie_id,
            (int) $data->quantite
        );

        Flight::redirect('/dons');
    }

    public function edit($id) {
        $don = $this->donModel->getById((int) $id);
        if (!$don) {
            Flight::redirect('/dons');
            return;
        }

        Flight::render('pages/dons/form', [
            'categories' => $this->categorieModel->getAll(),
            'don' => $don,
            'action' => '/dons/update/' . $id
        ]);
    }

    public function update($id) {
        $data = Flight::request()->data;

        $this->donModel->update(
            (int) $id,
            $data->nom,
            (int) $data->categorie_id,
            (int) $data->quantite
        );

        Flight::redirect('/dons');
    }

    public function delete($id) {
        $this->donModel->delete((int) $id);
        Flight::redirect('/dons');
    }
}
