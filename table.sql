-- Active: 1678729030280@@127.0.0.1@3306@focale

CREATE TABLE
    Utilisateur (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(120) NOT NULL,
        hash VARCHAR(60) NOT NULL,
        pseudo VARCHAR(255) NOT NULL,
        role ENUM(
            'utilisateur',
            'editeur',
            'admin'
        ) NOT NULL DEFAULT 'utilisateur',
        ville VARCHAR(255) NOT NULL,
        pays VARCHAR(255),
        date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

ALTER TABLE Utilisateur
ADD
    COLUMN actif BOOLEAN NOT NULL DEFAULT TRUE;

CREATE TABLE
    Article (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        titre VARCHAR(255) NOT NULL,
        contenu TEXT NOT NULL,
        slug VARCHAR(255),
        image VARCHAR(255),
        date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        id_utilisateur INT(11) UNSIGNED NOT NULL,
        FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id)
    );

CREATE TABLE
    Commentaire (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        message TEXT NOT NULL,
        date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        id_article INT(11) UNSIGNED NOT NULL,
        id_utilisateur INT(11) UNSIGNED NOT NULL,
        FOREIGN KEY (id_article) REFERENCES Article(id),
        FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id)
    );