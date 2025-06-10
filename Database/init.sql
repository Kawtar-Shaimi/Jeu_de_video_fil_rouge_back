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
