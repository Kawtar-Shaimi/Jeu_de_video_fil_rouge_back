CREATE DATABASE fil_rouge_rattrapage;

USE fil_rouge_rattrapage;

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150),
    photo VARCHAR(255),
    salary DECIMAL(10,2) NOT NULL,
    password VARCHAR(255)
);

-- Le mot de passe chiffré en Bcrypt c'est : 'PASSWORD'
INSERT INTO employees (name, email, photo, salary, password) VALUES
('Ahmed Mansour', 'ahmed.mansour@example.com', '/employees_images/ahmed.jpeg', 4500.00, '$2y$10$3ATbsSsRB/LAJXUCuutnJ.XHQsHIFaDzQDV0MPUIvMqEi4rKbXTwG'),
('Fatima Zahra', 'fatima.zahra@example.com', '/employees_images/fatima.jpg', 5200.50, '$2y$10$3ATbsSsRB/LAJXUCuutnJ.XHQsHIFaDzQDV0MPUIvMqEi4rKbXTwG'),
('Youssef Amrani', 'youssef.amrani@example.com', '/employees_images/youssef.jpeg', 6100.75, '$2y$10$3ATbsSsRB/LAJXUCuutnJ.XHQsHIFaDzQDV0MPUIvMqEi4rKbXTwG'),
('Samira Belkacem', 'samira.belkacem@example.com', '/employees_images/samira.jpg', 4800.00, '$2y$10$3ATbsSsRB/LAJXUCuutnJ.XHQsHIFaDzQDV0MPUIvMqEi4rKbXTwG'),
('Omar Elhadi', 'omar.elhadi@example.com', '/employees_images/omar.jpg', 5300.00, '$2y$10$3ATbsSsRB/LAJXUCuutnJ.XHQsHIFaDzQDV0MPUIvMqEi4rKbXTwG');


-- Table des clients
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE,
    phone VARCHAR(20)
);

-- Table des jeux (table de base pour l'héritage)
CREATE TABLE jeux (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    genre VARCHAR(100),
    editeur VARCHAR(100),
    stockDisponible INT NOT NULL DEFAULT 0,
    type_jeu ENUM('PC', 'Console') NOT NULL,
    -- Attributs spécifiques JeuPC
    configurationMinimale TEXT NULL,
    supportDVD BOOLEAN NULL,
    -- Attributs spécifiques JeuConsole  
    plateforme VARCHAR(50) NULL,
    regionCode VARCHAR(10) NULL
);

-- Table des ventes
CREATE TABLE ventes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    jeu_id INT NOT NULL,
    dateVente DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    quantite INT NOT NULL DEFAULT 1,
    montantTotal DECIMAL(10,2) NOT NULL,
    statut ENUM('PAYÉE', 'EN_ATTENTE', 'ANNULÉE') NOT NULL DEFAULT 'EN_ATTENTE',
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (jeu_id) REFERENCES jeux(id) ON DELETE CASCADE
);

-- Données d'exemple pour les clients
INSERT INTO clients (nom, email, phone) VALUES
('Ahmed Ben Ali', 'ahmed.benali@email.com', '0612345678'),
('Fatima Zahra', 'fatima.zahra@email.com', '0623456789'),
('Youssef Gaming', 'youssef.gamer@email.com', '0634567890'),
('Samira Tech', 'samira.tech@email.com', '0645678901');

-- Données d'exemple pour les jeux PC
INSERT INTO jeux (titre, prix, genre, editeur, stockDisponible, type_jeu, configurationMinimale, supportDVD) VALUES
('Cyberpunk 2077', 299.99, 'RPG', 'CD Projekt RED', 15, 'PC', 'Intel Core i5-3570K, 8GB RAM, GTX 780', true),
('The Witcher 3', 149.99, 'RPG', 'CD Projekt RED', 25, 'PC', 'Intel Core i5-2500K, 6GB RAM, GTX 660', true),
('Counter-Strike 2', 0.00, 'FPS', 'Valve', 1000, 'PC', 'Intel Core i5-750, 4GB RAM, GTX 460', false);

-- Données d'exemple pour les jeux Console
INSERT INTO jeux (titre, prix, genre, editeur, stockDisponible, type_jeu, plateforme, regionCode) VALUES
('God of War', 399.99, 'Action', 'Sony Interactive', 20, 'Console', 'PlayStation 5', 'EUR'),
('Halo Infinite', 349.99, 'FPS', 'Microsoft Studios', 18, 'Console', 'Xbox Series X', 'EUR'),
('Super Mario Odyssey', 299.99, 'Platformer', 'Nintendo', 12, 'Console', 'Nintendo Switch', 'EUR'),
('The Last of Us Part II', 279.99, 'Action', 'Naughty Dog', 8, 'Console', 'PlayStation 4', 'EUR');

-- Données d'exemple pour les ventes
INSERT INTO ventes (client_id, jeu_id, quantite, montantTotal, statut) VALUES
(1, 1, 1, 299.99, 'PAYÉE'),
(2, 4, 1, 399.99, 'PAYÉE'),
(3, 3, 2, 0.00, 'PAYÉE'),
(1, 6, 1, 299.99, 'EN_ATTENTE'),
(4, 2, 1, 149.99, 'ANNULÉE'); 