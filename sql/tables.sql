CREATE DATABASE mbnl;
USE mbnl;

CREATE TABLE utilisateur(
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    `password` VARCHAR(100),
    `role` VARCHAR(50) DEFAULT 'client'
);

CREATE TABLE category(
    id_category INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    `description` TEXT DEFAULT 'No description yet'
);

CREATE TABLE car(
    id_car INT PRIMARY KEY AUTO_INCREMENT,
    marque VARCHAR(50),
    model VARCHAR(50),
    prix DECIMAL(10,2),
    `image` VARCHAR(200),
    `status` VARCHAR(50) DEFAULT 'AVAILABLE',
    id_category INT,
    `description` TEXT DEFAULT 'No description yet',
    FOREIGN KEY (id_category) REFERENCES category(id_category)
);

CREATE TABLE reservation(
    id_reservation INT PRIMARY KEY AUTO_INCREMENT,
    id_client INT,
    id_car INT,
    date_reservation DATETIME DEFAULT CURRENT_TIMESTAMP,
    pickupLocation VARCHAR(100),
    retrounLocation VARCHAR(100),
    `status` VARCHAR DEFAULT 'PENDING',
    startDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    endDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES utilisateur(id_user),
    FOREIGN KEY (id_car) REFERENCES car(id_car)
);

CREATE TABLE avis(
    id_avis INT PRIMARY KEY AUTO_INCREMENT,
    note INT,
    texte TEXT,
    id_client INT,
    id_car INT,
    id_reservation INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES utilisateur(id_user),
    FOREIGN KEY (id_car) REFERENCES car(id_car),
    FOREIGN KEY (id_reservation) REFERENCES reservation(id_reservation)
);
