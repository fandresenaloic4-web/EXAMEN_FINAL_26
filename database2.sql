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
INSERT INTO categorie (nom) VALUES
('Nature'),
('Materiel'),
('Argent');

-- ============================================
-- RÉGIONS DE MADAGASCAR
-- ============================================
INSERT INTO region (nom) VALUES
('Analamanga'),        -- 1
('Vakinankaratra'),    -- 2
('Atsinanana'),        -- 3
('DIANA'),             -- 4
('Boeny'),             -- 5
('Atsimo-Andrefana'),  -- 6
('Haute Matsiatra'),   -- 7
('Alaotra-Mangoro'),   -- 8
('SAVA'),              -- 9
('Anosy');              -- 10

-- ============================================
-- VILLES
-- ============================================
INSERT INTO ville (nom, region_id) VALUES
-- Analamanga (1)
('Antananarivo', 1),         -- 1
('Ambohidratrimo', 1),       -- 2
('Ankazobe', 1),             -- 3
-- Vakinankaratra (2)
('Antsirabe', 2),            -- 4
('Ambatolampy', 2),          -- 5
-- Atsinanana (3)
('Toamasina', 3),            -- 6
('Brickaville', 3),          -- 7
-- DIANA (4)
('Antsiranana', 4),          -- 8
('Nosy Be', 4),              -- 9
-- Boeny (5)
('Mahajanga', 5),            -- 10
('Marovoay', 5),             -- 11
-- Atsimo-Andrefana (6)
('Toliara', 6),              -- 12
('Morondava', 6),            -- 13
-- Haute Matsiatra (7)
('Fianarantsoa', 7),         -- 14
('Ambalavao', 7),            -- 15
-- Alaotra-Mangoro (8)
('Ambatondrazaka', 8),       -- 16
('Moramanga', 8),            -- 17
-- SAVA (9)
('Sambava', 9),              -- 18
('Antalaha', 9),             -- 19
-- Anosy (10)
('Taolagnaro', 10),          -- 20
('Amboasary-Sud', 10);       -- 21

-- ============================================
-- BESOINS PAR VILLE
-- ============================================
INSERT INTO besoin (ville_id, categorie_id, libelle, prix_unitaire, quantite_demandee, quantite_restante) VALUES
-- Antananarivo (cyclone)
(1, 1, 'Riz (sacs de 50kg)', 85000.00, 500, 500),
(1, 1, 'Eau potable (bidons 20L)', 5000.00, 1000, 1000),
(1, 2, 'Tentes de secours', 250000.00, 100, 100),
(1, 2, 'Couvertures', 35000.00, 300, 300),
(1, 3, 'Aide financière urgente', 1.00, 50000000, 50000000),

-- Toamasina (inondation)
(6, 1, 'Riz (sacs de 50kg)', 85000.00, 800, 800),
(6, 1, 'Haricots secs (kg)', 8000.00, 500, 500),
(6, 2, 'Bâches plastiques', 45000.00, 200, 200),
(6, 2, 'Kits hygiène', 30000.00, 400, 400),
(6, 3, 'Reconstruction maisons', 1.00, 100000000, 100000000),

-- Mahajanga (sécheresse)
(10, 1, 'Riz (sacs de 50kg)', 85000.00, 600, 600),
(10, 1, 'Maïs (sacs de 25kg)', 40000.00, 400, 400),
(10, 1, 'Eau potable (bidons 20L)', 5000.00, 2000, 2000),
(10, 2, 'Médicaments de base', 120000.00, 150, 150),
(10, 3, 'Aide alimentaire', 1.00, 30000000, 30000000),

-- Fianarantsoa (glissement de terrain)
(14, 1, 'Riz (sacs de 50kg)', 85000.00, 300, 300),
(14, 2, 'Tentes de secours', 250000.00, 80, 80),
(14, 2, 'Outils de déblaiement', 75000.00, 50, 50),
(14, 3, 'Aide d\'urgence', 1.00, 20000000, 20000000),

-- Toliara (sécheresse sévère)
(12, 1, 'Riz (sacs de 50kg)', 85000.00, 1000, 1000),
(12, 1, 'Eau potable (bidons 20L)', 5000.00, 3000, 3000),
(12, 1, 'Manioc sec (kg)', 6000.00, 800, 800),
(12, 2, 'Citernes d\'eau', 500000.00, 20, 20),
(12, 2, 'Médicaments de base', 120000.00, 200, 200),
(12, 3, 'Programme alimentaire', 1.00, 80000000, 80000000),

-- Taolagnaro (cyclone)
(20, 1, 'Riz (sacs de 50kg)', 85000.00, 400, 400),
(20, 1, 'Huile alimentaire (L)', 12000.00, 300, 300),
(20, 2, 'Tôles de toiture', 55000.00, 500, 500),
(20, 2, 'Kits hygiène', 30000.00, 250, 250),
(20, 3, 'Reconstruction urgente', 1.00, 60000000, 60000000),

-- Sambava (cyclone)
(18, 1, 'Riz (sacs de 50kg)', 85000.00, 350, 350),
(18, 2, 'Bâches plastiques', 45000.00, 150, 150),
(18, 2, 'Couvertures', 35000.00, 200, 200),

-- Antsirabe (inondation)
(4, 1, 'Riz (sacs de 50kg)', 85000.00, 200, 200),
(4, 2, 'Tentes de secours', 250000.00, 40, 40),
(4, 3, 'Aide financière', 1.00, 15000000, 15000000),

-- Brickaville (inondation)
(7, 1, 'Riz (sacs de 50kg)', 85000.00, 450, 450),
(7, 1, 'Eau potable (bidons 20L)', 5000.00, 800, 800),
(7, 2, 'Médicaments de base', 120000.00, 100, 100),

-- Amboasary-Sud (famine - kere)
(21, 1, 'Riz (sacs de 50kg)', 85000.00, 1200, 1200),
(21, 1, 'Maïs (sacs de 25kg)', 40000.00, 600, 600),
(21, 1, 'Eau potable (bidons 20L)', 5000.00, 5000, 5000),
(21, 2, 'Médicaments malnutrition', 200000.00, 300, 300),
(21, 3, 'Programme nutritionnel', 1.00, 150000000, 150000000);

-- ============================================
-- DONS
-- ============================================
INSERT INTO don (nom, categorie_id, quantite) VALUES
-- Dons en nature
('Don riz - Croix Rouge', 1, 800),
('Don eau - UNICEF', 1, 3000),
('Don haricots - PAM', 1, 400),
('Don maïs - ONG Taratra', 1, 500),
('Don huile - Caritas', 1, 200),
('Don manioc - Communauté locale', 1, 300),

-- Dons en matériel
('Tentes - Croix Rouge Internationale', 2, 120),
('Bâches - BNGRC Stock', 2, 250),
('Couvertures - Caritas Madagascar', 2, 350),
('Kits hygiène - UNICEF', 2, 400),
('Médicaments - OMS', 2, 300),
('Tôles - Ambatovy Mining', 2, 200),
('Citernes - JIRAMA', 2, 15),
('Outils - Commune Tana', 2, 40),

-- Dons en argent
('Fonds d\'urgence - État Malgache', 3, 200000000),
('Aide - Banque Mondiale', 3, 150000000),
('Don - Union Européenne', 3, 100000000),
('Collecte - Diaspora', 3, 50000000);

-- ============================================
-- DISTRIBUTIONS
-- ============================================
INSERT INTO distribution (don_id, besoin_id, quantite_attribuee, date_distribution) VALUES
-- Distribution riz Croix Rouge -> Toliara, Amboasary, Toamasina
(1, 21, 300, '2026-01-15'),
(1, 43, 350, '2026-01-16'),
(1, 6, 150, '2026-01-17'),

-- Distribution eau UNICEF -> Toliara, Amboasary, Mahajanga
(2, 22, 1000, '2026-01-18'),
(2, 45, 1200, '2026-01-18'),
(2, 13, 800, '2026-01-19'),

-- Distribution haricots PAM -> Toamasina
(3, 7, 400, '2026-01-20'),

-- Distribution maïs ONG -> Mahajanga, Amboasary
(4, 12, 200, '2026-01-21'),
(4, 44, 300, '2026-01-21'),

-- Distribution tentes Croix Rouge -> Antananarivo, Fianarantsoa
(7, 3, 60, '2026-01-22'),
(7, 17, 50, '2026-01-22'),

-- Distribution bâches BNGRC -> Toamasina, Sambava
(8, 8, 150, '2026-01-23'),
(8, 33, 100, '2026-01-23'),

-- Distribution couvertures Caritas -> Antananarivo, Sambava
(9, 4, 200, '2026-01-24'),
(9, 34, 150, '2026-01-24'),

-- Distribution kits hygiène UNICEF -> Toamasina, Taolagnaro
(10, 9, 250, '2026-01-25'),
(10, 31, 150, '2026-01-25'),

-- Distribution médicaments OMS -> Toliara, Amboasary
(11, 25, 100, '2026-01-26'),
(11, 46, 200, '2026-01-26'),

-- Distribution tôles Ambatovy -> Taolagnaro
(12, 30, 200, '2026-01-27'),

-- Distribution fonds État -> Toamasina, Toliara, Amboasary
(15, 10, 40000000, '2026-01-28'),
(15, 26, 60000000, '2026-01-28'),
(15, 47, 100000000, '2026-01-29'),

-- Distribution aide Banque Mondiale -> Antananarivo, Taolagnaro
(16, 5, 50000000, '2026-01-30'),
(16, 32, 60000000, '2026-01-30'),

-- Distribution UE -> Fianarantsoa, Mahajanga
(17, 19, 20000000, '2026-02-01'),
(17, 15, 30000000, '2026-02-01'),

-- Distribution Diaspora -> Antsirabe, Brickaville
(18, 38, 15000000, '2026-02-02'),
(18, 42, 10000000, '2026-02-02');

-- ============================================
-- MISE À JOUR quantite_restante des besoins distribués
-- ============================================
-- Riz
UPDATE besoin SET quantite_restante = quantite_restante - 300 WHERE id = 21;
UPDATE besoin SET quantite_restante = quantite_restante - 350 WHERE id = 43;
UPDATE besoin SET quantite_restante = quantite_restante - 150 WHERE id = 6;

-- Eau
UPDATE besoin SET quantite_restante = quantite_restante - 1000 WHERE id = 22;
UPDATE besoin SET quantite_restante = quantite_restante - 1200 WHERE id = 45;
UPDATE besoin SET quantite_restante = quantite_restante - 800 WHERE id = 13;

-- Haricots
UPDATE besoin SET quantite_restante = quantite_restante - 400 WHERE id = 7;

-- Maïs
UPDATE besoin SET quantite_restante = quantite_restante - 200 WHERE id = 12;
UPDATE besoin SET quantite_restante = quantite_restante - 300 WHERE id = 44;

-- Tentes
UPDATE besoin SET quantite_restante = quantite_restante - 60 WHERE id = 3;
UPDATE besoin SET quantite_restante = quantite_restante - 50 WHERE id = 17;

-- Bâches
UPDATE besoin SET quantite_restante = quantite_restante - 150 WHERE id = 8;
UPDATE besoin SET quantite_restante = quantite_restante - 100 WHERE id = 33;

-- Couvertures
UPDATE besoin SET quantite_restante = quantite_restante - 200 WHERE id = 4;
UPDATE besoin SET quantite_restante = quantite_restante - 150 WHERE id = 34;

-- Kits hygiène
UPDATE besoin SET quantite_restante = quantite_restante - 250 WHERE id = 9;
UPDATE besoin SET quantite_restante = quantite_restante - 150 WHERE id = 31;

-- Médicaments
UPDATE besoin SET quantite_restante = quantite_restante - 100 WHERE id = 25;
UPDATE besoin SET quantite_restante = quantite_restante - 200 WHERE id = 46;

-- Tôles
UPDATE besoin SET quantite_restante = quantite_restante - 200 WHERE id = 30;

-- Argent
UPDATE besoin SET quantite_restante = quantite_restante - 40000000 WHERE id = 10;
UPDATE besoin SET quantite_restante = quantite_restante - 60000000 WHERE id = 26;
UPDATE besoin SET quantite_restante = quantite_restante - 100000000 WHERE id = 47;
UPDATE besoin SET quantite_restante = quantite_restante - 50000000 WHERE id = 5;
UPDATE besoin SET quantite_restante = quantite_restante - 60000000 WHERE id = 32;
UPDATE besoin SET quantite_restante = quantite_restante - 20000000 WHERE id = 19;
UPDATE besoin SET quantite_restante = quantite_restante - 30000000 WHERE id = 15;
UPDATE besoin SET quantite_restante = quantite_restante - 15000000 WHERE id = 38;
UPDATE besoin SET quantite_restante = quantite_restante - 10000000 WHERE id = 42;
