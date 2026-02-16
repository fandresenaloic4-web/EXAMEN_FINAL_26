<?php

namespace app\models;

use Flight;
use PDO;

class Region {
    private $db;

    public function __construct() {
        $this->db = Flight::db();
    }

    /**
     * Récupérer toutes les régions
     */
    public function getAll() {
        $sql = "SELECT * FROM region ORDER BY nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une région par ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM region WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insérer une nouvelle région
     */
    public function inserer($nom) {
        $sql = "INSERT INTO region (nom) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nom);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Mettre à jour une région
     */
    public function update($id, $nom) {
        $sql = "UPDATE region SET nom = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Supprimer une région
     */
    public function delete($id) {
        $sql = "DELETE FROM region WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
