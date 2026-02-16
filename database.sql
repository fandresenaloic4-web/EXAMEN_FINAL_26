CREATE DATABASE IF NOT EXISTS db_s2_ETU004059;
USE db_s2_ETU004059;

-- REGION
CREATE TABLE region (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(25) NOT NULL
);

-- VILLE
CREATE TABLE ville (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    region_id INT NOT NULL,
    FOREIGN KEY (region_id) REFERENCES region(id)
);

-- CATEGORIE
CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(25) NOT NULL
);

-- BESOIN (par ville)
CREATE TABLE besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ville_id INT NOT NULL,
    categorie_id INT NOT NULL,
    libelle VARCHAR(50) NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    quantite_demandee INT NOT NULL,
    quantite_restante INT NOT NULL,

    FOREIGN KEY (ville_id) REFERENCES ville(id),
    FOREIGN KEY (categorie_id) REFERENCES categorie(id)
);

-- DON
CREATE TABLE don (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    categorie_id INT NOT NULL,
    quantite INT NOT NULL,

    FOREIGN KEY (categorie_id) REFERENCES categorie(id)
);

-- DISTRIBUTION
CREATE TABLE distribution (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_id INT NOT NULL,
    besoin_id INT NOT NULL,
    quantite_attribuee INT NOT NULL,
    date_distribution DATE NOT NULL,

    FOREIGN KEY (don_id) REFERENCES don(id),
    FOREIGN KEY (besoin_id) REFERENCES besoin(id)
);

-- ACHAT (V2 - achats via dons en argent)
CREATE TABLE achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    besoin_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    frais_pourcent DECIMAL(5,2) NOT NULL DEFAULT 10,
    montant_ht DECIMAL(12,2) NOT NULL,
    montant_frais DECIMAL(12,2) NOT NULL,
    montant_total DECIMAL(12,2) NOT NULL,
    date_achat DATE NOT NULL,

    FOREIGN KEY (besoin_id) REFERENCES besoin(id)
);

INSERT INTO categorie (nom) VALUES
('Nature'),
('Materiel'),
('Argent');

INSERT INTO region (nom) VALUES ('Analamanga');

INSERT INTO ville (nom, region_id) VALUES
('Antananarivo', 1),
('Ambohidratrimo', 1);