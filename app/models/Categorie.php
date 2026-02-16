<?php

namespace app\models;

use Flight;
use PDO;

class Categorie {
    private $db;

    public function __construct() {
        $this->db = Flight::db();
    }

    /**
     * Récupérer toutes les catégories
     */
    public function getAll() {
        $sql = "SELECT * FROM categorie ORDER BY nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une catégorie par ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM categorie WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insérer une nouvelle catégorie
     */
    public function inserer($nom) {
        $sql = "INSERT INTO categorie (nom) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nom);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update($id, $nom) {
        $sql = "UPDATE categorie SET nom = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Supprimer une catégorie
     */
    public function delete($id) {
        $sql = "DELETE FROM categorie WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
