-- ============================================
-- BNGRC - Base de données complète
-- Bureau National de Gestion des Risques et des Catastrophes
-- ============================================

CREATE DATABASE IF NOT EXISTS db_s2_ETU004291;
USE db_s2_ETU004291;

-- ============================================
-- SUPPRESSION DES TABLES (si elles existent)
-- ============================================
DROP TABLE IF EXISTS achat;
DROP TABLE IF EXISTS distribution;
DROP TABLE IF EXISTS don;
DROP TABLE IF EXISTS besoin;
DROP TABLE IF EXISTS ville;
DROP TABLE IF EXISTS region;
DROP TABLE IF EXISTS categorie;

-- ============================================
-- CRÉATION DES TABLES
-- ============================================

-- REGION
CREATE TABLE region (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE,
    INDEX idx_region_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- VILLE
CREATE TABLE ville (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    region_id INT NOT NULL,
    FOREIGN KEY (region_id) REFERENCES region(id) ON DELETE CASCADE,
    INDEX idx_ville_region (region_id),
    INDEX idx_ville_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CATEGORIE
CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE,
    INDEX idx_categorie_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- BESOIN (par ville)
CREATE TABLE besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ville_id INT NOT NULL,
    categorie_id INT NOT NULL,
    libelle VARCHAR(255) NOT NULL,
    prix_unitaire DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    quantite_demandee INT NOT NULL DEFAULT 0,
    quantite_restante INT NOT NULL DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ville_id) REFERENCES ville(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categorie(id) ON DELETE CASCADE,
    INDEX idx_besoin_ville (ville_id),
    INDEX idx_besoin_categorie (categorie_id),
    INDEX idx_besoin_restante (quantite_restante),
    CHECK (quantite_demandee >= 0),
    CHECK (quantite_restante >= 0),
    CHECK (quantite_restante <= quantite_demandee),
    CHECK (prix_unitaire >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- DON
CREATE TABLE don (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    categorie_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 0,
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categorie(id) ON DELETE CASCADE,
    INDEX idx_don_categorie (categorie_id),
    INDEX idx_don_date (date_don),
    CHECK (quantite >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- DISTRIBUTION
CREATE TABLE distribution (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_id INT NOT NULL,
    besoin_id INT NOT NULL,
    quantite_attribuee INT NOT NULL DEFAULT 0,
    date_distribution DATE NOT NULL,
    FOREIGN KEY (don_id) REFERENCES don(id) ON DELETE CASCADE,
    FOREIGN KEY (besoin_id) REFERENCES besoin(id) ON DELETE CASCADE,
    INDEX idx_distribution_don (don_id),
    INDEX idx_distribution_besoin (besoin_id),
    INDEX idx_distribution_date (date_distribution),
    CHECK (quantite_attribuee > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ACHAT (V2 - achats via dons en argent)
CREATE TABLE achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    besoin_id INT NOT NULL,
    quantite INT NOT NULL DEFAULT 0,
    prix_unitaire DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    frais_pourcent DECIMAL(5,2) NOT NULL DEFAULT 10.00,
    montant_ht DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    montant_frais DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    montant_total DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    date_achat DATE NOT NULL,
    FOREIGN KEY (besoin_id) REFERENCES besoin(id) ON DELETE CASCADE,
    INDEX idx_achat_besoin (besoin_id),
    INDEX idx_achat_date (date_achat),
    CHECK (quantite > 0),
    CHECK (prix_unitaire >= 0),
    CHECK (frais_pourcent >= 0),
    CHECK (montant_ht >= 0),
    CHECK (montant_frais >= 0),
    CHECK (montant_total >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DONNÉES INITIALES
-- ============================================

-- Insertion des catégories
INSERT INTO categorie (nom) VALUES
('Nature'),
('Materiel'),
('Argent');

-- Insertion des régions
INSERT INTO region (nom) VALUES
('Analamanga'),
('Vakinankaratra'),
('Itasy'),
('Diana'),
('Sava'),
('Atsinanana');

-- Insertion des villes
INSERT INTO ville (nom, region_id) VALUES
-- Analamanga
('Antananarivo', 1),
('Ambohidratrimo', 1),
('Antsirabe', 2),
('Betafo', 2),
('Arivonimamo', 3),
('Miarinarivo', 3),
('Antsiranana', 4),
('Ambanja', 5),
('Toamasina', 6),
('Brickaville', 6);

-- ============================================
-- DONNÉES DE TEST (optionnel)
-- ============================================

-- Quelques besoins exemples
INSERT INTO besoin (ville_id, categorie_id, libelle, prix_unitaire, quantite_demandee, quantite_restante) VALUES
(1, 1, 'Riz (sacs de 50kg)', 45000, 100, 100),
(1, 2, 'Couvertures', 15000, 50, 50),
(1, 2, 'Bâches en plastique', 8000, 80, 80),
(2, 1, 'Eau potable (bidons 20L)', 2000, 200, 200),
(2, 2, 'Lampes torches', 5000, 40, 40),
(3, 1, 'Sucre (paquets 1kg)', 3500, 150, 150),
(3, 2, 'Savon', 1500, 100, 100);

-- Quelques dons exemples
INSERT INTO don (nom, categorie_id, quantite) VALUES
('Association Caritas', 1, 150),
('Croix Rouge Malagasy', 2, 200),
('Donateur Anonyme', 3, 5000000),
('ONG Solidarité', 1, 80),
('Entreprise JIRAMA', 2, 100);

-- ============================================
-- VUES UTILES (optionnel)
-- ============================================

-- Vue: Besoins avec détails
CREATE OR REPLACE VIEW v_besoins_details AS
SELECT 
    b.id,
    b.libelle,
    b.prix_unitaire,
    b.quantite_demandee,
    b.quantite_restante,
    (b.quantite_demandee - b.quantite_restante) AS quantite_attribuee,
    (b.prix_unitaire * b.quantite_demandee) AS montant_total,
    (b.prix_unitaire * (b.quantite_demandee - b.quantite_restante)) AS montant_couvert,
    (b.prix_unitaire * b.quantite_restante) AS montant_restant,
    v.nom AS ville_nom,
    r.nom AS region_nom,
    c.nom AS categorie_nom,
    b.date_creation
FROM besoin b
JOIN ville v ON b.ville_id = v.id
JOIN region r ON v.region_id = r.id
JOIN categorie c ON b.categorie_id = c.id;

-- Vue: Dons avec disponibilité
CREATE OR REPLACE VIEW v_dons_disponibilite AS
SELECT 
    d.id,
    d.nom,
    d.quantite AS quantite_totale,
    COALESCE(SUM(dist.quantite_attribuee), 0) AS quantite_distribuee,
    (d.quantite - COALESCE(SUM(dist.quantite_attribuee), 0)) AS quantite_disponible,
    c.nom AS categorie_nom,
    d.date_don
FROM don d
JOIN categorie c ON d.categorie_id = c.id
LEFT JOIN distribution dist ON dist.don_id = d.id
GROUP BY d.id, d.nom, d.quantite, c.nom, d.date_don;

-- Vue: Statistiques globales
CREATE OR REPLACE VIEW v_statistiques_globales AS
SELECT 
    (SELECT COUNT(*) FROM besoin) AS total_besoins,
    (SELECT SUM(quantite_demandee) FROM besoin) AS total_quantite_demandee,
    (SELECT SUM(quantite_restante) FROM besoin) AS total_quantite_restante,
    (SELECT COUNT(*) FROM don) AS total_dons,
    (SELECT SUM(quantite) FROM don) AS total_quantite_dons,
    (SELECT COUNT(*) FROM distribution) AS total_distributions,
    (SELECT SUM(quantite_attribuee) FROM distribution) AS total_quantite_distribuee,
    (SELECT COUNT(*) FROM achat) AS total_achats,
    (SELECT COALESCE(SUM(montant_total), 0) FROM achat) AS total_montant_achats;

-- ============================================
-- FIN DU SCRIPT
-- ============================================