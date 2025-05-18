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
   mat_pigiste INT,
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
   UNIQUE(type_canal),
   FOREIGN KEY(code) REFERENCES Numero(code),
   FOREIGN KEY(code_pays) REFERENCES Pays(code_pays)
);

CREATE TABLE contient(
   num_rubrique INT,
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
