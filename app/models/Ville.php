<?php

namespace app\models;

use Flight;
use PDO;

class Ville {
    private $db;

    public function __construct() {
        $this->db = Flight::db();
    }

    /**
     * Récupérer toutes les villes avec leur région
     */
    public function getAll() {
        $sql = "SELECT v.*, r.nom AS region_nom 
                FROM ville v 
                JOIN region r ON v.region_id = r.id 
                ORDER BY v.nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une ville par ID avec sa région
     */
    public function getById($id) {
        $sql = "SELECT v.*, r.nom AS region_nom 
                FROM ville v 
                JOIN region r ON v.region_id = r.id 
                WHERE v.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les villes par région
     */
    public function getByRegion($region_id) {
        $sql = "SELECT v.*, r.nom AS region_nom 
                FROM ville v 
                JOIN region r ON v.region_id = r.id 
                WHERE v.region_id = ? 
                ORDER BY v.nom";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $region_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insérer une nouvelle ville
     */
    public function inserer($nom, $region_id) {
        $sql = "INSERT INTO ville (nom, region_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $region_id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Mettre à jour une ville
     */
    public function update($id, $nom, $region_id) {
        $sql = "UPDATE ville SET nom = ?, region_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $region_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Supprimer une ville
     */
    public function delete($id) {
        $sql = "DELETE FROM ville WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
