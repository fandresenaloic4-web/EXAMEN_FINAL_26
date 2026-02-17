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
    public function dispatchDons($strategy = 'fifo') {
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
            // Récupérer besoins de même catégorie (ordre dépend de la stratégie)
            $orderBy = 'b.id';
            if ($strategy === 'smallest') {
                $orderBy = 'b.quantite_restante ASC';
            }

            $sql2 = "SELECT b.id, b.libelle, b.quantite_restante, v.nom AS ville_nom
                     FROM besoin b
                     JOIN ville v ON b.ville_id = v.id
                     WHERE b.categorie_id = ? AND b.quantite_restante > 0
                     ORDER BY " . $orderBy;
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->bindParam(1, $don['categorie_id'], PDO::PARAM_INT);
            $stmt2->execute();
            $besoins = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            if ($strategy === 'proportion') {
                // Distribuer proportionnellement selon quantite_restante
                $totalRest = 0;
                foreach ($besoins as $b) $totalRest += (int) $b['quantite_restante'];
                if ($totalRest <= 0) continue;

                // Calculer allocations initiales et fractions
                $allocs = [];
                $fractions = [];
                $sumAllocated = 0;
                foreach ($besoins as $b) {
                    $need = (int) $b['quantite_restante'];
                    $raw = ($disponible * $need) / $totalRest;
                    $floor = (int) floor($raw);
                    $alloc = min($floor, $need);
                    $allocs[$b['id']] = $alloc;
                    $fractions[$b['id']] = $raw - $floor;
                    $sumAllocated += $alloc;
                }

                // Distribuer le reste selon la plus grande fraction, sans dépasser le besoin
                $remainder = $disponible - $sumAllocated;
                if ($remainder > 0) {
                    arsort($fractions);
                    foreach ($fractions as $bid => $frac) {
                        if ($remainder <= 0) break;
                        // trouver capacité
                        $cap = null;
                        foreach ($besoins as $b) if ($b['id'] == $bid) { $cap = (int)$b['quantite_restante']; break; }
                        $current = $allocs[$bid];
                        if ($current < $cap) {
                            $allocs[$bid] = $current + 1;
                            $remainder -= 1;
                        }
                    }
                }

                // Appliquer allocations
                foreach ($besoins as $b) {
                    $bid = $b['id'];
                    $a_attribuer = isset($allocs[$bid]) ? (int)$allocs[$bid] : 0;
                    if ($a_attribuer <= 0) continue;

                    $this->inserer($don['id'], $bid, $a_attribuer, date('Y-m-d'));
                    $besoinModel = new Besoin();
                    $besoinModel->updateQuantiteRestante($bid, $a_attribuer);

                    $disponible -= $a_attribuer;

                    $log[] = [
                        'don' => $don['nom'],
                        'besoin' => $b['libelle'],
                        'ville' => $b['ville_nom'],
                        'quantite' => $a_attribuer
                    ];

                    if ($disponible <= 0) break;
                }

            } else {
                // FIFO ou smallest (ordre déjà pris en compte)
                foreach ($besoins as $besoin) {
                    if ($disponible <= 0) break;

                    $a_attribuer = min($disponible, (int) $besoin['quantite_restante']);
                    if ($a_attribuer <= 0) continue;

                    $this->inserer($don['id'], $besoin['id'], $a_attribuer, date('Y-m-d'));
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
        }

        return $log;
    }

    /**
     * Simulation du dispatch (sans écriture en BD)
     * Même logique que dispatchDons() mais en mémoire seulement
     */
    public function simulateDispatch($strategy = 'fifo') {
        $log = [];

        // Récupérer tous les dons avec quantité disponible
        $sql = "SELECT d.id, d.nom, d.categorie_id, d.quantite,
                       COALESCE(SUM(dist.quantite_attribuee), 0) AS distribue,
                       (d.quantite - COALESCE(SUM(dist.quantite_attribue), 0)) AS disponible
                FROM don d
                LEFT JOIN distribution dist ON dist.don_id = d.id
                GROUP BY d.id
                HAVING disponible > 0
                ORDER BY d.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $dons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tracker les quantités simulées en mémoire
        $besoinRestantSimule = [];

        foreach ($dons as $don) {
            $disponible = (int) $don['disponible'];
            if ($disponible <= 0) continue;

            // Récupérer besoins (ordre selon stratégie)
            $orderBy = ($strategy === 'smallest') ? 'b.quantite_restante ASC' : 'b.id';
            $sql2 = "SELECT b.id, b.libelle, b.quantite_restante, v.nom AS ville_nom
                     FROM besoin b
                     JOIN ville v ON b.ville_id = v.id
                     WHERE b.categorie_id = ? AND b.quantite_restante > 0
                     ORDER BY " . $orderBy;
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->bindParam(1, $don['categorie_id'], PDO::PARAM_INT);
            $stmt2->execute();
            $besoins = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            if ($strategy === 'proportion') {
                $totalRest = 0;
                foreach ($besoins as $b) {
                    $id = $b['id'];
                    $rest = isset($besoinRestantSimule[$id]) ? $besoinRestantSimule[$id] : (int)$b['quantite_restante'];
                    $totalRest += $rest;
                }
                if ($totalRest <= 0) continue;

                $allocs = [];
                $fractions = [];
                $sumAlloc = 0;
                foreach ($besoins as $b) {
                    $id = $b['id'];
                    $rest = isset($besoinRestantSimule[$id]) ? $besoinRestantSimule[$id] : (int)$b['quantite_restante'];
                    if ($rest <= 0) { $allocs[$id] = 0; $fractions[$id] = 0; continue; }
                    $raw = ($disponible * $rest) / $totalRest;
                    $floor = (int) floor($raw);
                    $alloc = min($floor, $rest);
                    $allocs[$id] = $alloc;
                    $fractions[$id] = $raw - $floor;
                    $sumAlloc += $alloc;
                }

                $remainder = $disponible - $sumAlloc;
                if ($remainder > 0) {
                    arsort($fractions);
                    foreach ($fractions as $bid => $frac) {
                        if ($remainder <= 0) break;
                        $cap = isset($besoinRestantSimule[$bid]) ? $besoinRestantSimule[$bid] : null;
                        if ($cap === null) {
                            foreach ($besoins as $b) if ($b['id'] == $bid) { $cap = (int)$b['quantite_restante']; break; }
                        }
                        $current = $allocs[$bid];
                        if ($current < $cap) { $allocs[$bid] = $current + 1; $remainder -= 1; }
                    }
                }

                foreach ($allocs as $bid => $a_attribuer) {
                    if ($a_attribuer <= 0) continue;
                    $restAvant = isset($besoinRestantSimule[$bid]) ? $besoinRestantSimule[$bid] : null;
                    if ($restAvant === null) {
                        foreach ($besoins as $b) if ($b['id'] == $bid) { $restAvant = (int)$b['quantite_restante']; $lib = $b['libelle']; $ville = $b['ville_nom']; break; }
                    } else {
                        foreach ($besoins as $b) if ($b['id'] == $bid) { $lib = $b['libelle']; $ville = $b['ville_nom']; break; }
                    }
                    $besoinRestantSimule[$bid] = max(0, $restAvant - $a_attribuer);
                    $disponible -= $a_attribuer;
                    $log[] = ['don' => $don['nom'], 'besoin' => $lib, 'ville' => $ville, 'quantite' => $a_attribuer];
                    if ($disponible <= 0) break;
                }

            } else {
                foreach ($besoins as $besoin) {
                    if ($disponible <= 0) break;
                    $id = $besoin['id'];
                    $restant = isset($besoinRestantSimule[$id]) ? $besoinRestantSimule[$id] : (int)$besoin['quantite_restante'];
                    if ($restant <= 0) continue;
                    $a_attribuer = min($disponible, $restant);
                    $besoinRestantSimule[$id] = $restant - $a_attribuer;
                    $disponible -= $a_attribuer;
                    $log[] = ['don' => $don['nom'], 'besoin' => $besoin['libelle'], 'ville' => $besoin['ville_nom'], 'quantite' => $a_attribuer];
                }
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
