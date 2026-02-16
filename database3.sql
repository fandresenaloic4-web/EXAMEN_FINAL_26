-- ============================================
-- DONNÉES DE TEST - BNGRC Madagascar
-- ============================================

USE db_s2_ETU004059;

-- Vider les tables (ordre inverse des dépendances)
DELETE FROM distribution;
DELETE FROM don;
DELETE FROM besoin;
DELETE FROM ville;
DELETE FROM region;
DELETE FROM categorie;

-- Reset auto-increment
ALTER TABLE distribution AUTO_INCREMENT = 1;
ALTER TABLE don AUTO_INCREMENT = 1;
ALTER TABLE besoin AUTO_INCREMENT = 1;
ALTER TABLE ville AUTO_INCREMENT = 1;
ALTER TABLE region AUTO_INCREMENT = 1;
ALTER TABLE categorie AUTO_INCREMENT = 1;

-- ============================================
-- CATÉGORIES
-- ============================================
INSERT INTO categorie (id, nom) VALUES
(1, 'Nature'),
(2, 'Materiel'),
(3, 'Argent');

-- ============================================
-- RÉGIONS DE MADAGASCAR
-- ============================================
INSERT INTO region (id, nom) VALUES
(1, 'Analamanga'),
(2, 'Vakinankaratra'),
(3, 'Atsinanana'),
(4, 'DIANA'),
(5, 'Boeny'),
(6, 'Atsimo-Andrefana'),
(7, 'Haute Matsiatra'),
(8, 'Alaotra-Mangoro'),
(9, 'SAVA'),
(10, 'Anosy');

-- ============================================
-- VILLES
-- ============================================
INSERT INTO ville (id, nom, region_id) VALUES
(1, 'Antananarivo', 1),
(2, 'Ambohidratrimo', 1),
(3, 'Ankazobe', 1),
(4, 'Antsirabe', 2),
(5, 'Ambatolampy', 2),
(6, 'Toamasina', 3),
(7, 'Brickaville', 3),
(8, 'Antsiranana', 4),
(9, 'Nosy Be', 4),
(10, 'Mahajanga', 5),
(11, 'Marovoay', 5),
(12, 'Toliara', 6),
(13, 'Morondava', 6),
(14, 'Fianarantsoa', 7),
(15, 'Ambalavao', 7),
(16, 'Ambatondrazaka', 8),
(17, 'Moramanga', 8),
(18, 'Sambava', 9),
(19, 'Antalaha', 9),
(20, 'Taolagnaro', 10),
(21, 'Amboasary-Sud', 10);

-- ============================================
-- BESOINS PAR VILLE
-- quantite_restante = quantite_demandee (avant distributions)
-- ============================================
INSERT INTO besoin (id, ville_id, categorie_id, libelle, prix_unitaire, quantite_demandee, quantite_restante) VALUES
-- Antananarivo (cyclone)
-- id=1: riz 500, distribué 150 => reste 350
(1, 1, 1, 'Riz (sacs de 50kg)', 85000.00, 500, 350),
-- id=2: eau 1000, distribué 0 => reste 1000
(2, 1, 1, 'Eau potable (bidons 20L)', 5000.00, 1000, 1000),
-- id=3: tentes 100, distribué 60 => reste 40
(3, 1, 2, 'Tentes de secours', 250000.00, 100, 40),
-- id=4: couvertures 300, distribué 200 => reste 100
(4, 1, 2, 'Couvertures', 35000.00, 300, 100),
-- id=5: argent 50M, distribué 25M => reste 25M
(5, 1, 3, 'Aide financière urgente', 1.00, 50000000, 25000000),

-- Toamasina (inondation)
-- id=6: riz 800, distribué 150 => reste 650
(6, 6, 1, 'Riz (sacs de 50kg)', 85000.00, 800, 650),
-- id=7: haricots 500, distribué 300 => reste 200
(7, 6, 1, 'Haricots secs (kg)', 8000.00, 500, 200),
-- id=8: bâches 200, distribué 100 => reste 100
(8, 6, 2, 'Bâches plastiques', 45000.00, 200, 100),
-- id=9: kits hygiène 400, distribué 200 => reste 200
(9, 6, 2, 'Kits hygiène', 30000.00, 400, 200),
-- id=10: argent 100M, distribué 40M => reste 60M
(10, 6, 3, 'Reconstruction maisons', 1.00, 100000000, 60000000),

-- Mahajanga (sécheresse)
-- id=11: riz 600, distribué 0 => reste 600
(11, 10, 1, 'Riz (sacs de 50kg)', 85000.00, 600, 600),
-- id=12: maïs 400, distribué 200 => reste 200
(12, 10, 1, 'Maïs (sacs de 25kg)', 40000.00, 400, 200),
-- id=13: eau 2000, distribué 500 => reste 1500
(13, 10, 1, 'Eau potable (bidons 20L)', 5000.00, 2000, 1500),
-- id=14: médicaments 150, distribué 0 => reste 150
(14, 10, 2, 'Médicaments de base', 120000.00, 150, 150),
-- id=15: argent 30M, distribué 15M => reste 15M
(15, 10, 3, 'Aide alimentaire', 1.00, 30000000, 15000000),

-- Fianarantsoa (glissement de terrain)
-- id=16: riz 300, distribué 0 => reste 300
(16, 14, 1, 'Riz (sacs de 50kg)', 85000.00, 300, 300),
-- id=17: tentes 80, distribué 50 => reste 30
(17, 14, 2, 'Tentes de secours', 250000.00, 80, 30),
-- id=18: outils 50, distribué 30 => reste 20
(18, 14, 2, 'Outils de déblaiement', 75000.00, 50, 20),
-- id=19: argent 20M, distribué 10M => reste 10M
(19, 14, 3, 'Aide d\'urgence', 1.00, 20000000, 10000000),

-- Toliara (sécheresse sévère)
-- id=20: riz 1000, distribué 300 => reste 700
(20, 12, 1, 'Riz (sacs de 50kg)', 85000.00, 1000, 700),
-- id=21: eau 3000, distribué 800 => reste 2200
(21, 12, 1, 'Eau potable (bidons 20L)', 5000.00, 3000, 2200),
-- id=22: manioc 800, distribué 200 => reste 600
(22, 12, 1, 'Manioc sec (kg)', 6000.00, 800, 600),
-- id=23: citernes 20, distribué 10 => reste 10
(23, 12, 2, 'Citernes d\'eau', 500000.00, 20, 10),
-- id=24: médicaments 200, distribué 80 => reste 120
(24, 12, 2, 'Médicaments de base', 120000.00, 200, 120),
-- id=25: argent 80M, distribué 30M => reste 50M
(25, 12, 3, 'Programme alimentaire', 1.00, 80000000, 50000000),

-- Taolagnaro (cyclone)
-- id=26: riz 400, distribué 100 => reste 300
(26, 20, 1, 'Riz (sacs de 50kg)', 85000.00, 400, 300),
-- id=27: huile 300, distribué 0 => reste 300
(27, 20, 1, 'Huile alimentaire (L)', 12000.00, 300, 300),
-- id=28: tôles 500, distribué 200 => reste 300
(28, 20, 2, 'Tôles de toiture', 55000.00, 500, 300),
-- id=29: kits hygiène 250, distribué 100 => reste 150
(29, 20, 2, 'Kits hygiène', 30000.00, 250, 150),
-- id=30: argent 60M, distribué 30M => reste 30M
(30, 20, 3, 'Reconstruction urgente', 1.00, 60000000, 30000000),

-- Sambava (cyclone)
-- id=31: riz 350, distribué 0 => reste 350
(31, 18, 1, 'Riz (sacs de 50kg)', 85000.00, 350, 350),
-- id=32: bâches 150, distribué 80 => reste 70
(32, 18, 2, 'Bâches plastiques', 45000.00, 150, 70),
-- id=33: couvertures 200, distribué 100 => reste 100
(33, 18, 2, 'Couvertures', 35000.00, 200, 100),

-- Antsirabe (inondation)
-- id=34: riz 200, distribué 0 => reste 200
(34, 4, 1, 'Riz (sacs de 50kg)', 85000.00, 200, 200),
-- id=35: tentes 40, distribué 0 => reste 40
(35, 4, 2, 'Tentes de secours', 250000.00, 40, 40),
-- id=36: argent 15M, distribué 8M => reste 7M
(36, 4, 3, 'Aide financière', 1.00, 15000000, 7000000),

-- Brickaville (inondation)
-- id=37: riz 450, distribué 100 => reste 350
(37, 7, 1, 'Riz (sacs de 50kg)', 85000.00, 450, 350),
-- id=38: eau 800, distribué 0 => reste 800
(38, 7, 1, 'Eau potable (bidons 20L)', 5000.00, 800, 800),
-- id=39: médicaments 100, distribué 50 => reste 50
(39, 7, 2, 'Médicaments de base', 120000.00, 100, 50),

-- Amboasary-Sud (famine - kere)
-- id=40: riz 1200, distribué 350 => reste 850
(40, 21, 1, 'Riz (sacs de 50kg)', 85000.00, 1200, 850),
-- id=41: maïs 600, distribué 250 => reste 350
(41, 21, 1, 'Maïs (sacs de 25kg)', 40000.00, 600, 350),
-- id=42: eau 5000, distribué 1000 => reste 4000
(42, 21, 1, 'Eau potable (bidons 20L)', 5000.00, 5000, 4000),
-- id=43: médicaments malnutrition 300, distribué 100 => reste 200
(43, 21, 2, 'Médicaments malnutrition', 200000.00, 300, 200),
-- id=44: argent 150M, distribué 50M => reste 100M
(44, 21, 3, 'Programme nutritionnel', 1.00, 150000000, 100000000);

-- ============================================
-- DONS
-- ============================================
INSERT INTO don (id, nom, categorie_id, quantite) VALUES
-- Dons en nature
(1, 'Don riz - Croix Rouge', 1, 800),
(2, 'Don eau - UNICEF', 1, 3000),
(3, 'Don haricots - PAM', 1, 400),
(4, 'Don maïs - ONG Taratra', 1, 500),
(5, 'Don huile - Caritas', 1, 200),
(6, 'Don manioc - Communauté locale', 1, 300),

-- Dons en matériel
(7, 'Tentes - Croix Rouge Internationale', 2, 120),
(8, 'Bâches - BNGRC Stock', 2, 250),
(9, 'Couvertures - Caritas Madagascar', 2, 350),
(10, 'Kits hygiène - UNICEF', 2, 400),
(11, 'Médicaments - OMS', 2, 300),
(12, 'Tôles - Ambatovy Mining', 2, 200),
(13, 'Citernes - JIRAMA', 2, 15),
(14, 'Outils - Commune Tana', 2, 40),

-- Dons en argent
(15, 'Fonds d urgence - État Malgache', 3, 200000000),
(16, 'Aide - Banque Mondiale', 3, 150000000),
(17, 'Don - Union Européenne', 3, 100000000),
(18, 'Collecte - Diaspora', 3, 50000000);

-- ============================================
-- DISTRIBUTIONS
-- ============================================
INSERT INTO distribution (don_id, besoin_id, quantite_attribuee, date_distribution) VALUES
(1, 1, 150, '2026-01-15'),
(1, 6, 150, '2026-01-16'),
(1, 20, 300, '2026-01-17'),
(1, 26, 100, '2026-01-17'),
(1, 37, 100, '2026-01-18'),
(2, 21, 800, '2026-01-18'),
(2, 13, 500, '2026-01-19'),
(2, 42, 1000, '2026-01-19'),
(3, 7, 300, '2026-01-20'),
(4, 12, 200, '2026-01-21'),
(4, 41, 250, '2026-01-21'),
(6, 22, 200, '2026-01-22'),
(7, 3, 60, '2026-01-22'),
(7, 17, 50, '2026-01-23'),
(8, 8, 100, '2026-01-23'),
(8, 32, 80, '2026-01-24'),
(9, 4, 200, '2026-01-24'),
(9, 33, 100, '2026-01-25'),
(10, 9, 200, '2026-01-25'),
(10, 29, 100, '2026-01-26'),
(11, 24, 80, '2026-01-26'),
(11, 39, 50, '2026-01-27'),
(11, 43, 100, '2026-01-27'),
(12, 28, 200, '2026-01-28'),
(13, 23, 10, '2026-01-28'),
(14, 18, 30, '2026-01-29'),
(15, 10, 40000000, '2026-01-30'),
(15, 25, 30000000, '2026-01-30'),
(15, 44, 50000000, '2026-01-31'),
(16, 5, 25000000, '2026-02-01'),
(16, 30, 30000000, '2026-02-01'),
(17, 19, 10000000, '2026-02-02'),
(17, 15, 15000000, '2026-02-02'),
(18, 36, 8000000, '2026-02-03');

