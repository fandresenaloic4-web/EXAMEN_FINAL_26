<?php

namespace app\models;

use Flight;
use PDO;

class Distribution {
    private $db;

    public function __construct() {
        $this->db = Flight::db();
    }

    /**
     * Récupérer toutes les distributions avec détails
     */
    public function getAll() {
        $sql = "SELECT dist.*, 
                       d.nom AS don_nom, 
                       b.libelle AS besoin_libelle,
                       v.nom AS ville_nom,
                       c.nom AS categorie_nom
                FROM distribution dist
                JOIN don d ON dist.don_id = d.id
                JOIN besoin b ON dist.besoin_id = b.id
                JOIN ville v ON b.ville_id = v.id
                JOIN categorie c ON b.categorie_id = c.id
                ORDER BY dist.date_distribution DESC, dist.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une distribution par ID
     */
    public function getById($id) {
        $sql = "SELECT dist.*, 
                       d.nom AS don_nom, 
                       b.libelle AS besoin_libelle,
                       v.nom AS ville_nom,
                       c.nom AS categorie_nom
                FROM distribution dist
                JOIN don d ON dist.don_id = d.id
                JOIN besoin b ON dist.besoin_id = b.id
                JOIN ville v ON b.ville_id = v.id
                JOIN categorie c ON b.categorie_id = c.id
                WHERE dist.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les distributions par don
     */
    public function getByDon($don_id) {
        $sql = "SELECT dist.*, 
                       b.libelle AS besoin_libelle,
                       v.nom AS ville_nom
                FROM distribution dist
                JOIN besoin b ON dist.besoin_id = b.id
                JOIN ville v ON b.ville_id = v.id
                WHERE dist.don_id = ?
                ORDER BY dist.date_distribution DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $don_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les distributions par besoin
     */
    public function getByBesoin($besoin_id) {
        $sql = "SELECT dist.*, 
                       d.nom AS don_nom
                FROM distribution dist
                JOIN don d ON dist.don_id = d.id
                WHERE dist.besoin_id = ?
                ORDER BY dist.date_distribution DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $besoin_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insérer une nouvelle distribution
     */
    public function inserer($don_id, $besoin_id, $quantite_attribuee, $date_distribution) {
        $sql = "INSERT INTO distribution (don_id, besoin_id, quantite_attribuee, date_distribution)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $don_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $besoin_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $quantite_attribuee, PDO::PARAM_INT);
        $stmt->bindParam(4, $date_distribution);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Supprimer une distribution
     */
    public function delete($id) {
        $sql = "DELETE FROM distribution WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Dispatch automatique des dons par ordre de saisie
     * Distribue les dons disponibles vers les besoins de même catégorie
     */
    public function dispatchDons() {
        $log = [];

        // Récupérer tous les dons avec quantité disponible, ordonnés par id (ordre de saisie)
        $sql = "SELECT d.id, d.nom, d.categorie_id, d.quantite,
                       COALESCE(SUM(dist.quantite_attribuee), 0) AS distribue,
                       (d.quantite - COALESCE(SUM(dist.quantite_attribuee), 0)) AS disponible
                FROM don d
                LEFT JOIN distribution dist ON dist.don_id = d.id
                GROUP BY d.id
                HAVING disponible > 0
                ORDER BY d.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $dons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dons as $don) {
            $disponible = (int) $don['disponible'];
            if ($disponible <= 0) continue;

            // Trouver les besoins de même catégorie avec quantité restante
            $sql2 = "SELECT b.id, b.libelle, b.quantite_restante, v.nom AS ville_nom
                     FROM besoin b
                     JOIN ville v ON b.ville_id = v.id
                     WHERE b.categorie_id = ? AND b.quantite_restante > 0
                     ORDER BY b.id";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->bindParam(1, $don['categorie_id'], PDO::PARAM_INT);
            $stmt2->execute();
            $besoins = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            foreach ($besoins as $besoin) {
                if ($disponible <= 0) break;

                $a_attribuer = min($disponible, (int) $besoin['quantite_restante']);

                // Créer la distribution
                $this->inserer($don['id'], $besoin['id'], $a_attribuer, date('Y-m-d'));

                // Mettre à jour le besoin
                $besoinModel = new Besoin();
                $besoinModel->updateQuantiteRestante($besoin['id'], $a_attribuer);

                $disponible -= $a_attribuer;

                $log[] = [
                    'don' => $don['nom'],
                    'besoin' => $besoin['libelle'],
                    'ville' => $besoin['ville_nom'],
                    'quantite' => $a_attribuer
                ];
            }
        }

        return $log;
    }

    /**
     * Récupérer les distributions par date
     */
    public function getByDate($date) {
        $sql = "SELECT dist.*, 
                       d.nom AS don_nom, 
                       b.libelle AS besoin_libelle,
                       v.nom AS ville_nom,
                       c.nom AS categorie_nom
                FROM distribution dist
                JOIN don d ON dist.don_id = d.id
                JOIN besoin b ON dist.besoin_id = b.id
                JOIN ville v ON b.ville_id = v.id
                JOIN categorie c ON b.categorie_id = c.id
                WHERE dist.date_distribution = ?
                ORDER BY dist.id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les distributions par plage de dates
     */
    public function getByDateRange($date_debut, $date_fin) {
        $sql = "SELECT dist.*, 
                       d.nom AS don_nom, 
                       b.libelle AS besoin_libelle,
                       v.nom AS ville_nom,
                       c.nom AS categorie_nom
                FROM distribution dist
                JOIN don d ON dist.don_id = d.id
                JOIN besoin b ON dist.besoin_id = b.id
                JOIN ville v ON b.ville_id = v.id
                JOIN categorie c ON b.categorie_id = c.id
                WHERE dist.date_distribution BETWEEN ? AND ?
                ORDER BY dist.date_distribution DESC, dist.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $date_debut);
        $stmt->bindParam(2, $date_fin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
