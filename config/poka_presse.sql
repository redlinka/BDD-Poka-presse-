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

CREATE TABLE Region(
   code_pays VARCHAR(50),
   nom_pays VARCHAR(50),
   PRIMARY KEY(code_pays)
);

CREATE TABLE Maquette(
   num_vers SERIAL,
   date_creation DATE,
   lien_maquette VARCHAR(50),
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
   FOREIGN KEY(code) REFERENCES Numero(code),
   FOREIGN KEY(code_pays) REFERENCES Region(code_pays)
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
('Dupont', 'Jean', 'jean.dupont@example.com', TRUE, 'AD', 'pass123'),
('Bernard', 'Luc', 'luc.bernard@example.com', TRUE, 'PG', NULL),
('Durand', 'Alice', 'alice.durand@example.com', FALSE, 'PG', NULL),
('Petit', 'Julien', 'julien.petit@example.com', TRUE, 'MQ', 'mqpass1'),
('Moreau', 'Nina', 'nina.moreau@example.com', TRUE, 'PG', NULL),        
('Fournier', 'Eric', 'eric.fournier@example.com', FALSE, 'PG', NULL),
('Roux', 'Laura', 'laura.roux@example.com', TRUE, 'PG', NULL),
('Blanc', 'Thomas', 'thomas.blanc@example.com', TRUE, 'MQ', 'mgpass2'),
('Garnier', 'Hugo', 'hugo.garnier@example.com', FALSE, 'MQ', 'mqsecure2'),
('Chevalier', 'Emma', 'emma.chevalier@example.com', TRUE, 'PG', NULL),
('Faure', 'Louis', 'louis.faure@example.com', TRUE, 'MQ', 'mgpass3'),   
('Barbier', 'Julie', 'julie.barbier@example.com', FALSE, 'PG', NULL),
('Girard', 'Leo', 'leo.girard@example.com', TRUE, 'PG', NULL),
('Andre', 'Isabelle', 'isabelle.andre@example.com', TRUE, 'MQ', 'mqsafe3'),
('Marchand', 'Benoit', 'benoit.marchand@example.com', FALSE, 'PG', NULL),
('Collet', 'Eva', 'eva.collet@example.com', TRUE, 'PG', NULL),      
('Renard', 'Paul', 'paul.renard@example.com', TRUE, 'PG', NULL),
('Perrin', 'Elise', 'elise.perrin@example.com', FALSE, 'MQ', 'mqopen4'),
('Leroy', 'Kevin', 'kevin.leroy@example.com', TRUE, 'PG', NULL),
('Nguyen', 'Mai', 'mai.nguyen@example.com', FALSE, 'PG', NULL),
('Henry', 'Arthur', 'arthur.henry@example.com', TRUE, 'PG', NULL),
('Meyer', 'Lucie', 'lucie.meyer@example.com', TRUE, 'MQ', 'mgtrusted'),
('Renaud', 'Axel', 'axel.renaud@example.com', TRUE, 'MQ', 'mq1'),
('Delcourt', 'Manon', 'manon.delcourt@example.com', TRUE, 'MQ', 'crpass001'),
('Benoit', 'Marc', 'marc.benoit@example.com', FALSE, 'CR', 'crpass002'),
('Lange', 'Sarah', 'sarah.lange@example.com', TRUE, 'CR', 'crpass003');

-- hash passwords
UPDATE Acteur SET pass = md5(pass);

-- Pigiste (mat_pigiste references Acteur.matricule)
INSERT INTO Pigiste (mat_pigiste, notoriete) VALUES
(1, 60.25),
(4, 80.00),
(5, 55.75),
(7, 90.10),
(8, 70.00),
(9, 65.30),
(12, 85.00),
(14, 77.20),
(15, 68.40),
(17, 72.50),
(18, 66.60),
(19, 88.80),
(21, 63.90),
(22, 79.99),
(23, 74.25);


-- Rubrique (hierarchical, num_rubrique_ancetre references same table)
INSERT INTO Rubrique (nom_rubrique, num_rubrique_ancetre) VALUES
('Challenges', NULL),
('Le dossier du mois', NULL),
('L’enjeu du mois', NULL),
('Portrait', NULL),
('Interview', NULL),
('Relation clients', NULL),
('Fidélisation', NULL),
('Zapping', NULL),
('Efficacité professionnelle', NULL),
('Développement personnel', NULL),
('Fiches pratiques', NULL);     

-- Region
INSERT INTO Region (code_pays, nom_pays) VALUES
('MP', 'France metropolitaine'),
('RE', 'Réunion'),
('MQ', 'Martinique'),
('GP', 'Guadeloupe'),
('GF', 'Guyane'),
('NC', 'Nouvelle-Calédonie'),
('PF', 'Polynésie française'),
('YT', 'Mayotte'),
('PM', 'Saint-Pierre-et-Miquelon'),
('WF', 'Wallis et Futuna'),
('BL', 'Saint-Barthélemy'),
('MF', 'Saint-Martin'),
('SX', 'Sint Maarten (partie néerlandaise)');


INSERT INTO Maquette (date_creation, lien_maquette, mat_maquettiste) VALUES
('2025-01-01', 'maquette_2025_01.pdf', 3),
('2025-02-15', 'maquette_2025_02.pdf', 1);

INSERT INTO Numero (code, date_publication, num_vers) VALUES
('NUM001', '2025-02-01', 1),
('NUM002', '2025-03-01', 2),
('NUM003', NULL, NULL),
('NUM004', NULL, NULL);

INSERT INTO distribution (code, code_pays, type_canal, nb_ventes, date_vente) VALUES
('NUM001', 'RE', 'Kiosque', 5000, '2025-02-05'),
('NUM001', 'MP', 'Abonnement', 3000, '2025-02-05'),
('NUM002', 'MP', 'Kiosque', 2000, '2025-03-10'),
('NUM002', 'GP', 'Abonnement', 15000, '2025-03-10'),
('NUM003', 'GF', 'Kiosque', 1000, '2025-03-10'),
('NUM004', 'PF', 'Abonnement', 500, '2025-03-10');

-- Contient (num_rubrique references Rubrique.num_rubrique, code references Numero.code)
INSERT INTO contient (num_rubrique, code) VALUES
(1, 'NUM001'),
(3, 'NUM002');

-- Article (num_rubrique references Rubrique.num_rubrique)
INSERT INTO Article (titre, chapeau, lien_contenu, date_acceptation, publie, nb_feuillets, num_rubrique) VALUES
('Les nouveaux défis', 'Présentation des challenges à venir', 'challenges_2025.html', '2025-01-10', TRUE, 4, 1),
('Dossier : Transformation digitale', 'Analyse approfondie du dossier du mois', 'dossier_mois_2025.html', '2025-01-12', TRUE, 6, 2),
('L’enjeu de la fidélisation', 'Focus sur l’enjeu du mois', 'enjeu_fidelisation.html', '2025-01-15', TRUE, 3, 3),
('Portrait : Marie Curie', 'Portrait inspirant du mois', 'portrait_curie.html', '2025-01-18', TRUE, 2, 4),
('Interview exclusive', 'Entretien avec un expert du secteur', 'interview_expert.html', '2025-01-20', TRUE, 5, 5),
('Relation clients : bonnes pratiques', 'Conseils pour améliorer la relation client', 'relation_clients.html', '2025-01-22', FALSE, 3, 6),
('Fidélisation : cas d’étude', 'Étude de cas sur la fidélisation', 'fidelisation_cas.html', '2025-01-25', TRUE, 4, 7),
('Zapping : l’actualité en bref', 'Résumé des faits marquants', 'zapping_2025.html', '2025-01-27', TRUE, 2, 8),
('Efficacité professionnelle', 'Astuces pour gagner en efficacité', 'efficacite_prof.html', '2025-01-29', TRUE, 3, 9),
('Développement personnel', 'Développer ses compétences', 'developpement_perso.html', '2025-02-01', TRUE, 3, 10),
('Fiche pratique : gestion du temps', 'Outils pour mieux gérer son temps', 'fiche_gestion_temps.html', '2025-02-03', TRUE, 2, 11);

-- Ecriture (num_article references Article.num_article, mat_pigiste references Pigiste.mat_pigiste)
INSERT INTO ecriture (num_article, mat_pigiste) VALUES
(1, 1),
(2, 4),
(3, 5),
(4, 7),
(5, 8),
(6, 9),
(7, 12),
(8, 14),
(9, 15),
(10, 17),
(11, 18);

-- Image
INSERT INTO Image (lien_image) VALUES
('img_challenges_2025.jpg'),
('img_transformation_digitale.jpg'),
('img_enjeu_fidelisation.jpg'),
('img_portrait_curie.jpg'),
('img_interview_expert.jpg'),
('img_relation_clients.jpg'),
('img_fidelisation_cas.jpg'),
('img_zapping_2025.jpg'),
('img_efficacite_prof.jpg'),
('img_developpement_perso.jpg'),
('img_gestion_temps.jpg'),
('sommaire1.jpg'),
('sommaire2.jpg');


-- Liaison (num_article references Article.num_article, num_image references Image.num_image)
INSERT INTO liaison (num_article, num_image) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11);

-- Apparition (num_image references Image.num_image, num_vers references Maquette.num_vers)
INSERT INTO apparition (num_image, num_vers) VALUES
(12, 1),
(13, 2);
