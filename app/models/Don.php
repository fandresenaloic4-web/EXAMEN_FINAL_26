<?php

namespace app\models;

use Flight;
use PDO;

class Don {
    private $db;

    public function __construct() {
        $this->db = Flight::db();
    }

    /**
     * Récupérer tous les dons avec catégorie et quantité disponible
     */
    public function getAll() {
        $sql = "SELECT d.*, c.nom AS categorie_nom,
                       COALESCE(SUM(dist.quantite_attribuee), 0) AS quantite_distribuee,
                       (d.quantite - COALESCE(SUM(dist.quantite_attribuee), 0)) AS quantite_disponible
                FROM don d
                JOIN categorie c ON d.categorie_id = c.id
                LEFT JOIN distribution dist ON dist.don_id = d.id
                GROUP BY d.id
                ORDER BY d.nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un don par ID avec quantité disponible
     */
    public function getById($id) {
        $sql = "SELECT d.*, c.nom AS categorie_nom,
                       COALESCE(SUM(dist.quantite_attribuee), 0) AS quantite_distribuee,
                       (d.quantite - COALESCE(SUM(dist.quantite_attribuee), 0)) AS quantite_disponible
                FROM don d
                JOIN categorie c ON d.categorie_id = c.id
                LEFT JOIN distribution dist ON dist.don_id = d.id
                WHERE d.id = ?
                GROUP BY d.id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les dons disponibles par catégorie
     */
    public function getAvailableByCategorie($categorie_id) {
        $sql = "SELECT d.*, c.nom AS categorie_nom,
                       COALESCE(SUM(dist.quantite_attribuee), 0) AS quantite_distribuee,
                       (d.quantite - COALESCE(SUM(dist.quantite_attribuee), 0)) AS quantite_disponible
                FROM don d
                JOIN categorie c ON d.categorie_id = c.id
                LEFT JOIN distribution dist ON dist.don_id = d.id
                WHERE d.categorie_id = ?
                GROUP BY d.id
                HAVING quantite_disponible > 0
                ORDER BY d.nom";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $categorie_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insérer un nouveau don
     */
    public function inserer($nom, $categorie_id, $quantite) {
        $sql = "INSERT INTO don (nom, categorie_id, quantite) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $categorie_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $quantite, PDO::PARAM_INT);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Mettre à jour un don
     */
    public function update($id, $nom, $categorie_id, $quantite) {
        $sql = "UPDATE don SET nom = ?, categorie_id = ?, quantite = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $nom);
        $stmt->bindParam(2, $categorie_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $quantite, PDO::PARAM_INT);
        $stmt->bindParam(4, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Supprimer un don
     */
    public function delete($id) {
        // Supprimer d'abord les distributions associées
        $sql = "DELETE FROM distribution WHERE don_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        // Ensuite supprimer le don
        $sql = "DELETE FROM don WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Statistiques globales des dons
     */
    public function getStats() {
        $sql = "SELECT 
                    COUNT(*) AS total_dons,
                    COALESCE(SUM(d.quantite), 0) AS total_quantite,
                    COALESCE(SUM(dist_sum.total_distribue), 0) AS total_distribue
                FROM don d
                LEFT JOIN (
                    SELECT don_id, SUM(quantite_attribuee) AS total_distribue
                    FROM distribution
                    GROUP BY don_id
                ) dist_sum ON dist_sum.don_id = d.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Rechercher des dons par nom
     */
    public function search($terme) {
        $sql = "SELECT d.*, c.nom AS categorie_nom,
                       COALESCE(SUM(dist.quantite_attribuee), 0) AS quantite_distribuee,
                       (d.quantite - COALESCE(SUM(dist.quantite_attribuee), 0)) AS quantite_disponible
                FROM don d
                JOIN categorie c ON d.categorie_id = c.id
                LEFT JOIN distribution dist ON dist.don_id = d.id
                WHERE d.nom LIKE ?
                GROUP BY d.id
                ORDER BY d.nom";
        $stmt = $this->db->prepare($sql);
        $terme = "%{$terme}%";
        $stmt->bindParam(1, $terme);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
