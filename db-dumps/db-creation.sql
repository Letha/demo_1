DROP DATABASE IF EXISTS demo_1;

CREATE DATABASE demo_1
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_general_ci;

USE demo_1;

CREATE TABLE accounts (
    login VARCHAR(255) PRIMARY KEY,
    password_hash VARCHAR(255) NOT NULL
) ENGINE InnoDB;

CREATE TABLE users (
    login VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255),
    surname VARCHAR(255),

    FOREIGN KEY (login) REFERENCES accounts (login)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE InnoDB;

CREATE TABLE users_images_files (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    login VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    MIME_type VARCHAR(255) NOT NULL,
    path_in_filesystem VARCHAR(255) NOT NULL,

    FOREIGN KEY (login) REFERENCES accounts (login)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE InnoDB;

CREATE TABLE invitations (
    code VARCHAR(255) PRIMARY KEY
) ENGINE InnoDB;
INSERT INTO invitations (code) VALUES ('9DR-2G5-1J2');