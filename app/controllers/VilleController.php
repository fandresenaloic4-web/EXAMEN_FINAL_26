<?php

namespace app\controllers;

use Flight;
use app\models\Ville;
use app\models\Region;

class VilleController {
    private $villeModel;
    private $regionModel;

    public function __construct() {
        $this->villeModel = new Ville();
        $this->regionModel = new Region();
    }

    public function index() {
        $regionId = Flight::request()->query->region_id ?? null;
        $regionId = $regionId ? (int) $regionId : null;

        if ($regionId) {
            $villes = $this->villeModel->getByRegion($regionId);
        } else {
            $villes = $this->villeModel->getAll();
        }

        Flight::render('pages/villes/index', [
            'villes' => $villes,
            'regions' => $this->regionModel->getAll(),
            'selectedRegion' => $regionId
        ]);
    }

    public function create() {
        Flight::render('pages/villes/form', [
            'regions' => $this->regionModel->getAll(),
            'ville' => null,
            'action' => '/villes/store'
        ]);
    }

    public function store() {
        $data = Flight::request()->data;

        $this->villeModel->inserer(
            $data->nom,
            (int) $data->region_id
        );

        Flight::redirect('/villes');
    }

    public function edit($id) {
        $ville = $this->villeModel->getById((int) $id);
        if (!$ville) {
            Flight::redirect('/villes');
            return;
        }

        Flight::render('pages/villes/form', [
            'regions' => $this->regionModel->getAll(),
            'ville' => $ville,
            'action' => '/villes/update/' . $id
        ]);
    }

    public function update($id) {
        $data = Flight::request()->data;

        $this->villeModel->update(
            (int) $id,
            $data->nom,
            (int) $data->region_id
        );

        Flight::redirect('/villes');
    }

    public function delete($id) {
        $this->villeModel->delete((int) $id);
        Flight::redirect('/villes');
    }
}
