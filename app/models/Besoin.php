<?php

namespace app\models;

use Flight;
use PDO;

class Besoin {
    private $db;

    public function __construct() {
        $this->db = Flight::db();
    }

    /**
     * Récupérer tous les besoins avec ville et catégorie
     */
    public function getAll() {
        $sql = "SELECT b.*, v.nom AS ville_nom, c.nom AS categorie_nom,
                       (b.prix_unitaire * b.quantite_demandee) AS montant_total,
                       (b.prix_unitaire * (b.quantite_demandee - b.quantite_restante)) AS montant_couvert
                FROM besoin b
                JOIN ville v ON b.ville_id = v.id
                JOIN categorie c ON b.categorie_id = c.id
                ORDER BY b.quantite_restante DESC, v.nom, c.nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un besoin par ID
     */
    public function getById($id) {
        $sql = "SELECT b.*, v.nom AS ville_nom, c.nom AS categorie_nom
                FROM besoin b
                JOIN ville v ON b.ville_id = v.id
                JOIN categorie c ON b.categorie_id = c.id
                WHERE b.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les besoins par ville
     */
    public function getByVille($ville_id) {
        $sql = "SELECT b.*, c.nom AS categorie_nom,
                       (b.prix_unitaire * b.quantite_demandee) AS montant_total
                FROM besoin b
                JOIN categorie c ON b.categorie_id = c.id
                WHERE b.ville_id = ?
                ORDER BY c.nom";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $ville_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les besoins par catégorie
     */
    public function getByCategorie($categorie_id) {
        $sql = "SELECT b.*, v.nom AS ville_nom, c.nom AS categorie_nom
                FROM besoin b
                JOIN ville v ON b.ville_id = v.id
                JOIN categorie c ON b.categorie_id = c.id
                WHERE b.categorie_id = ?
                ORDER BY b.quantite_restante DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $categorie_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insérer un nouveau besoin
     */
    public function inserer($ville_id, $categorie_id, $libelle, $prix_unitaire, $quantite_demandee) {
        $sql = "INSERT INTO besoin (ville_id, categorie_id, libelle, prix_unitaire, quantite_demandee, quantite_restante)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $ville_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $categorie_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $libelle);
        $stmt->bindParam(4, $prix_unitaire);
        $stmt->bindParam(5, $quantite_demandee, PDO::PARAM_INT);
        $stmt->bindParam(6, $quantite_demandee, PDO::PARAM_INT); // quantite_restante = quantite_demandee au début
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Mettre à jour un besoin
     */
    public function update($id, $ville_id, $categorie_id, $libelle, $prix_unitaire, $quantite_demandee) {
        // Recalculer quantite_restante selon les distributions existantes
        $besoin = $this->getById($id);
        $distribue = $besoin['quantite_demandee'] - $besoin['quantite_restante'];
        $nouvelle_restante = max(0, $quantite_demandee - $distribue);

        $sql = "UPDATE besoin SET ville_id = ?, categorie_id = ?, libelle = ?, prix_unitaire = ?, 
                quantite_demandee = ?, quantite_restante = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $ville_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $categorie_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $libelle);
        $stmt->bindParam(4, $prix_unitaire);
        $stmt->bindParam(5, $quantite_demandee, PDO::PARAM_INT);
        $stmt->bindParam(6, $nouvelle_restante, PDO::PARAM_INT);
        $stmt->bindParam(7, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Mettre à jour la quantité restante d'un besoin
     */
    public function updateQuantiteRestante($id, $quantite_attribuee) {
        $sql = "UPDATE besoin SET quantite_restante = quantite_restante - ? WHERE id = ? AND quantite_restante >= ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $quantite_attribuee, PDO::PARAM_INT);
        $stmt->bindParam(2, $id, PDO::PARAM_INT);
        $stmt->bindParam(3, $quantite_attribuee, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Supprimer un besoin
     */
    public function delete($id) {
        // Supprimer d'abord les distributions associées
        $sql = "DELETE FROM distribution WHERE besoin_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        // Ensuite supprimer le besoin
        $sql = "DELETE FROM besoin WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Données du tableau de bord : besoins par ville avec dons attribués
     */
    public function getDashboardData() {
        $sql = "SELECT v.id AS ville_id, v.nom AS ville_nom, r.id AS region_id, r.nom AS region_nom,
                       b.id AS besoin_id, b.libelle, b.prix_unitaire, b.quantite_demandee, b.quantite_restante,
                       c.nom AS categorie_nom,
                       (b.quantite_demandee - b.quantite_restante) AS quantite_attribuee_total,
                       (b.prix_unitaire * b.quantite_demandee) AS montant_total,
                       (b.prix_unitaire * (b.quantite_demandee - b.quantite_restante)) AS montant_couvert
                FROM ville v
                JOIN region r ON v.region_id = r.id
                LEFT JOIN besoin b ON b.ville_id = v.id
                LEFT JOIN categorie c ON b.categorie_id = c.id
                ORDER BY v.nom, c.nom, b.libelle";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Statistiques globales des besoins
     */
    public function getStats() {
        $sql = "SELECT 
                    COUNT(*) AS total_besoins,
                    COALESCE(SUM(quantite_demandee), 0) AS total_demande,
                    COALESCE(SUM(quantite_demandee - quantite_restante), 0) AS total_attribue,
                    COALESCE(SUM(quantite_restante), 0) AS total_restant,
                    COALESCE(SUM(prix_unitaire * quantite_demandee), 0) AS montant_total,
                    COALESCE(SUM(prix_unitaire * (quantite_demandee - quantite_restante)), 0) AS montant_couvert
                FROM besoin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
