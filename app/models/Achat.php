<?php

namespace app\models;

use Flight;
use PDO;

class Achat {
    private $db;

    public function __construct() {
        $this->db = Flight::db();
    }

    /**
     * Récupérer tous les achats avec détails besoin et ville
     */
    public function getAll($villeId = null) {
        $sql = "SELECT a.*, b.libelle AS besoin_libelle, b.categorie_id,
                       v.nom AS ville_nom, c.nom AS categorie_nom
                FROM achat a
                JOIN besoin b ON a.besoin_id = b.id
                JOIN ville v ON b.ville_id = v.id
                JOIN categorie c ON b.categorie_id = c.id";
        
        $params = [];
        if ($villeId) {
            $sql .= " WHERE b.ville_id = ?";
            $params[] = $villeId;
        }
        
        $sql .= " ORDER BY a.date_achat DESC, a.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un achat par ID
     */
    public function getById($id) {
        $sql = "SELECT a.*, b.libelle AS besoin_libelle, b.categorie_id,
                       v.nom AS ville_nom, c.nom AS categorie_nom
                FROM achat a
                JOIN besoin b ON a.besoin_id = b.id
                JOIN ville v ON b.ville_id = v.id
                JOIN categorie c ON b.categorie_id = c.id
                WHERE a.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insérer un nouvel achat
     */
    public function inserer($besoinId, $quantite, $prixUnitaire, $fraisPourcent) {
        $montantHt = $prixUnitaire * $quantite;
        $montantFrais = $montantHt * ($fraisPourcent / 100);
        $montantTotal = $montantHt + $montantFrais;

        $sql = "INSERT INTO achat (besoin_id, quantite, prix_unitaire, frais_pourcent, montant_ht, montant_frais, montant_total, date_achat)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $date = date('Y-m-d');
        $stmt->bindParam(1, $besoinId, PDO::PARAM_INT);
        $stmt->bindParam(2, $quantite, PDO::PARAM_INT);
        $stmt->bindParam(3, $prixUnitaire);
        $stmt->bindParam(4, $fraisPourcent);
        $stmt->bindParam(5, $montantHt);
        $stmt->bindParam(6, $montantFrais);
        $stmt->bindParam(7, $montantTotal);
        $stmt->bindParam(8, $date);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Supprimer un achat
     */
    public function delete($id) {
        $sql = "DELETE FROM achat WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Montant total des dons en argent disponibles pour achats
     */
    public function MontantArgentDisponible() {
        // Total des dons en argent
        $sql = "SELECT COALESCE(SUM(d.quantite), 0) AS total_argent
                FROM don d
                JOIN categorie c ON d.categorie_id = c.id
                WHERE LOWER(c.nom) = 'argent'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $totalArgent = (float) $stmt->fetch(PDO::FETCH_ASSOC)['total_argent'];

        // Total déjà distribué en argent
        $sql2 = "SELECT COALESCE(SUM(dist.quantite_attribuee), 0) AS distribue_argent
                 FROM distribution dist
                 JOIN don d ON dist.don_id = d.id
                 JOIN categorie c ON d.categorie_id = c.id
                 WHERE LOWER(c.nom) = 'argent'";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->execute();
        $distribueArgent = (float) $stmt2->fetch(PDO::FETCH_ASSOC)['distribue_argent'];

        // Total déjà dépensé en achats
        $sql3 = "SELECT COALESCE(SUM(montant_total), 0) AS total_achats FROM achat";
        $stmt3 = $this->db->prepare($sql3);
        $stmt3->execute();
        $totalAchats = (float) $stmt3->fetch(PDO::FETCH_ASSOC)['total_achats'];

        return $totalArgent - $distribueArgent - $totalAchats;
    }

    /**
     * Statistiques des achats
     */
    public function getStats() {
        $sql = "SELECT 
                    COUNT(*) AS total_achats,
                    COALESCE(SUM(quantite), 0) AS total_quantite,
                    COALESCE(SUM(montant_ht), 0) AS total_montant_ht,
                    COALESCE(SUM(montant_frais), 0) AS total_frais,
                    COALESCE(SUM(montant_total), 0) AS total_montant
                FROM achat";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
