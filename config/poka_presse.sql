CREATE TABLE Acteur(
   matricule SERIAL,
   nom VARCHAR(50),
   prenom VARCHAR(50),
   mail VARCHAR(50),
   salarie BOOLEAN NOT NULL,
   fonction CHAR(2),
   pass VARCHAR(50),
   PRIMARY KEY(matricule)
);

CREATE TABLE Rubrique(
   num_rubrique SERIAL,
   nom_rubrique VARCHAR(50),
   num_rubrique_ancetre INT,
   PRIMARY KEY(num_rubrique),
   FOREIGN KEY(num_rubrique_ancetre) REFERENCES Rubrique(num_rubrique)
);

CREATE TABLE Article(
   num_article SERIAL,
   titre VARCHAR(50),
   chapeau VARCHAR(50),
   lien_contenu VARCHAR(50),
   date_acceptation DATE,
   publie BOOLEAN NOT NULL,
   nb_feuillets INT,
   num_rubrique INT NOT NULL,
   PRIMARY KEY(num_article),
   FOREIGN KEY(num_rubrique) REFERENCES Rubrique(num_rubrique)
);

CREATE TABLE Image(
   num_image SERIAL,
   lien_image VARCHAR(50),
   PRIMARY KEY(num_image),
   UNIQUE(lien_image)
);

CREATE TABLE Pigiste(
   mat_pigiste SERIAL,
   notoriete DECIMAL(10, 2),
   PRIMARY KEY(mat_pigiste),
   FOREIGN KEY(mat_pigiste) REFERENCES Acteur(matricule)
);

CREATE TABLE Pays(
   code_pays VARCHAR(50),
   nom_pays VARCHAR(50),
   statistiques_vente VARCHAR(50),
   PRIMARY KEY(code_pays)
);

CREATE TABLE Maquette(
   num_vers SERIAL,
   date_creation DATE,
   lien_maquette VARCHAR(50)
   mat_maquettiste INT,
   PRIMARY KEY(num_vers),
   FOREIGN KEY(mat_maquettiste) REFERENCES Acteur(matricule)
);

CREATE TABLE Numero(
   code VARCHAR(50),
   date_publication DATE,
   num_vers INT,
   PRIMARY KEY(code),
   FOREIGN KEY(num_vers) REFERENCES Maquette(num_vers)
);

CREATE TABLE distribution(
   code VARCHAR(50),
   code_pays VARCHAR(50),
   type_canal VARCHAR(50),
   nb_ventes INT,
   date_vente DATE,
   PRIMARY KEY(code, code_pays),
   UNIQUE(type_canal),
   FOREIGN KEY(code) REFERENCES Numero(code),
   FOREIGN KEY(code_pays) REFERENCES Pays(code_pays)
);

CREATE TABLE contient(
   num_rubrique SERIAL,
   code VARCHAR(50),
   PRIMARY KEY(num_rubrique, code),
   FOREIGN KEY(num_rubrique) REFERENCES Rubrique(num_rubrique),
   FOREIGN KEY(code) REFERENCES Numero(code)
);

CREATE TABLE ecriture(
   num_article INT,
   mat_pigiste INT,
   PRIMARY KEY(num_article, mat_pigiste),
   FOREIGN KEY(num_article) REFERENCES Article(num_article),
   FOREIGN KEY(mat_pigiste) REFERENCES Pigiste(mat_pigiste)
);

CREATE TABLE liaison(
   num_article INT,
   num_image INT,
   PRIMARY KEY(num_article, num_image),
   FOREIGN KEY(num_article) REFERENCES Article(num_article),
   FOREIGN KEY(num_image) REFERENCES Image(num_image)
);

CREATE TABLE apparition(
   num_image INT,
   num_vers INT,
   PRIMARY KEY(num_image, num_vers),
   FOREIGN KEY(num_image) REFERENCES Image(num_image),
   FOREIGN KEY(num_vers) REFERENCES Maquette(num_vers)
);

-- Acteur
INSERT INTO Acteur (nom, prenom, mail, salarie, fonction, pass) VALUES
('Martin', 'Claire', 'claire.martin@example.com', FALSE, 'PG', 'pass456'),
('Lemoine', 'Sophie', 'sophie.lemoine@example.com', TRUE, 'MQ', 'pass789'),
('Dupont', 'Jean', 'jean.dupont@example.com', TRUE, 'MQ', 'pass123'),
('Bernard', 'Luc', 'luc.bernard@example.com', TRUE, 'PG', NULL),
('Durand', 'Alice', 'alice.durand@example.com', FALSE, 'PG', NULL),
('Petit', 'Julien', 'julien.petit@example.com', TRUE, 'MQ', 'mqpass1'),
('Moreau', 'Nina', 'nina.moreau@example.com', TRUE, 'PG', NULL),         -- was MG
('Fournier', 'Eric', 'eric.fournier@example.com', FALSE, 'PG', NULL),
('Roux', 'Laura', 'laura.roux@example.com', TRUE, 'PG', NULL),
('Blanc', 'Thomas', 'thomas.blanc@example.com', TRUE, 'MQ', 'mgpass2'), -- was MG
('Garnier', 'Hugo', 'hugo.garnier@example.com', FALSE, 'MQ', 'mqsecure2'),
('Chevalier', 'Emma', 'emma.chevalier@example.com', TRUE, 'PG', NULL),
('Faure', 'Louis', 'louis.faure@example.com', TRUE, 'MQ', 'mgpass3'),   -- was MG
('Barbier', 'Julie', 'julie.barbier@example.com', FALSE, 'PG', NULL),
('Girard', 'Leo', 'leo.girard@example.com', TRUE, 'PG', NULL),
('Andre', 'Isabelle', 'isabelle.andre@example.com', TRUE, 'MQ', 'mqsafe3'),
('Marchand', 'Benoit', 'benoit.marchand@example.com', FALSE, 'PG', NULL),
('Collet', 'Eva', 'eva.collet@example.com', TRUE, 'PG', NULL),          -- was MG
('Renard', 'Paul', 'paul.renard@example.com', TRUE, 'PG', NULL),
('Perrin', 'Elise', 'elise.perrin@example.com', FALSE, 'MQ', 'mqopen4'),
('Leroy', 'Kevin', 'kevin.leroy@example.com', TRUE, 'PG', NULL),
('Nguyen', 'Mai', 'mai.nguyen@example.com', FALSE, 'PG', NULL),
('Henry', 'Arthur', 'arthur.henry@example.com', TRUE, 'PG', NULL),
('Meyer', 'Lucie', 'lucie.meyer@example.com', TRUE, 'MQ', 'mgtrusted'), -- was MG
('Renaud', 'Axel', 'axel.renaud@example.com', TRUE, 'MQ', 'mq1'),
('Delcourt', 'Manon', 'manon.delcourt@example.com', TRUE, 'CR', 'crpass001'),
('Benoit', 'Marc', 'marc.benoit@example.com', FALSE, 'CR', 'crpass002'),
('Lange', 'Sarah', 'sarah.lange@example.com', TRUE, 'CR', 'crpass003');

-- Pigiste (mat_pigiste references Acteur.matricule)
INSERT INTO Pigiste (mat_pigiste, notoriete) VALUES
(2, 75.50);

-- Rubrique (hierarchical, num_rubrique_ancetre references same table)
INSERT INTO Rubrique (nom_rubrique, num_rubrique_ancetre) VALUES
('Actualités', NULL),       -- num_rubrique = 1
('Sports', 1),             -- num_rubrique = 2
('Football', 2);           -- num_rubrique = 3

-- Pays
INSERT INTO Pays (code_pays, nom_pays, statistiques_vente) VALUES
('FR', 'France', 'Bonne'),
('US', 'United States', 'Excellente');

-- Maquette (mat_maquettiste references Acteur.matricule)
INSERT INTO Maquette (date_creation, lien_maquette, mat_maquettiste) VALUES
('2025-01-01', 'maquette_2025_01.pdf', 3),
('2025-02-15', 'maquette_2025_02.pdf', 1);

-- Numero (num_vers references Maquette.num_vers)
INSERT INTO Numero (code, date_publication, num_vers) VALUES
('NUM001', '2025-02-01', 1),
('NUM002', '2025-03-01', 2),
('NUM003', NULL, NULL),
('NUM004', NULL, NULL);

-- Distribution (code references Numero.code, code_pays references Pays.code_pays)
INSERT INTO distribution (code, code_pays, type_canal, nb_ventes, date_vente) VALUES
('NUM001', 'FR', 'Kiosque', 5000, '2025-02-05'),
('NUM001', 'US', 'Abonnement', 3000, '2025-02-05');

-- Contient (num_rubrique references Rubrique.num_rubrique, code references Numero.code)
INSERT INTO contient (num_rubrique, code) VALUES
(1, 'NUM001'),
(3, 'NUM002');

-- Article (num_rubrique references Rubrique.num_rubrique)
INSERT INTO Article (titre, chapeau, lien_contenu, date_acceptation, publie, nb_feuillets, num_rubrique) VALUES
('Nouvelles du football', 'Résumé des matchs récents', 'lien1.html', '2025-01-20', TRUE, 5, 3),
('Analyse économique', 'Point sur économie actuelle', 'lien2.html', '2025-01-15', FALSE, 3, 1);

-- Ecriture (num_article references Article.num_article, mat_pigiste references Pigiste.mat_pigiste)
INSERT INTO ecriture (num_article, mat_pigiste) VALUES
(1, 2);

-- Image
INSERT INTO Image (lien_image) VALUES
('img_football_01.jpg'),
('img_economie_01.jpg');

-- Liaison (num_article references Article.num_article, num_image references Image.num_image)
INSERT INTO liaison (num_article, num_image) VALUES
(1, 1),
(2, 2);

-- Apparition (num_image references Image.num_image, num_vers references Maquette.num_vers)
INSERT INTO apparition (num_image, num_vers) VALUES
(1, 1),
(2, 2);
